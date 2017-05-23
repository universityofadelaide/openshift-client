<?php

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase {

  private $host;
  private $token;
  private $namespace;
  private $json;

  protected $client;

  public function setUp() {
    global $argv, $argc;

    $this->assertEquals(6, $argc, 'Missing arguments');
    if (file_exists($argv[5])) {
      $this->host = $argv[2];
      $this->token = $argv[3];
      $this->namespace = $argv[4];
      $this->json = json_decode(file_get_contents($argv[5]));
      if (!is_object($this->json)) {
        die("Unable to decode json config\n");
      }
    }
    else {
      die("Unable to open specified file $argv[5]");
    }

    $this->client = new Client($this->host, $this->token, $this->namespace, TRUE);

  }

  public function testGetGuzzleClient() {
    // Test creating the client
    $this->assertInstanceOf(
      GuzzleClient::class,
      $this->client->getGuzzleClient(),
      'Unable to create Guzzle client.'
    );
  }

  public function testCreateSecret() {

    $request = $this->client->createSecret($this->json->clientTest->secret->name, [
      'username' => $this->json->clientTest->secret->user,
      'password' => $this->json->clientTest->secret->pass,
    ]);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create secret.'
    );
  }

  public function testUpdateSecret() {

    $request = $this->client->updateSecret($this->json->clientTest->secret->name, [
      'username' => $this->json->clientTest->secret->user,
      'password' => $this->json->clientTest->secret->alt_pass,
    ]);

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to update secret.'
    );
  }

  public function testCreateImageStream() {

    $request = $this->client->createImageStream($this->json->clientTest->image_stream);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create image stream.'
    );
  }

  public function testCreateBuildConfig() {
    $data = [
      'git' => [
        'uri' => $this->json->clientTest->source->git->uri,
        'ref' => $this->json->clientTest->source->git->ref,
      ],
      'source' => [
        'type' => $this->json->clientTest->sourceStrategy->from->kind,
        'name' => $this->json->clientTest->sourceStrategy->from->name,
      ],
    ];

    $request = $this->client->createBuildConfig($this->json->clientTest->artifacts . '-build', $this->json->clientTest->secret->name, $this->json->clientTest->image_stream, $data);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create build config.'
    );
  }

  public function testCreateDeploymentConfig() {
    $data = [
      'containerPort' => 8080,
      'memory_limit' => 128,
      'env_vars' => $this->json->clientTest->envVars,
    ];
    $name = $this->json->clientTest->artifacts . '-deploy';
    $image_stream_tag = $this->json->clientTest->image_stream;
    $image_name = $this->json->clientTest->image_name;

    $request = $this->client->createDeploymentConfig($name, $image_stream_tag, $image_name, $data);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create deployment config.'
    );
  }

  public function testDeleteDeploymentConfig() {
    if ($this->json->clientTest->delete) {

      $request = $this->client->deleteDeploymentConfig($this->json->clientTest->artifacts . '-deploy');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete deploy config.'
      );
    }
  }

  public function testDeleteBuildConfig() {
    if ($this->json->clientTest->delete) {

      $request = $this->client->deleteBuildConfig($this->json->clientTest->artifacts . '-build');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete build config.'
      );
    }
  }

  public function testDeleteImageStream() {
    if ($this->json->clientTest->delete) {

      $request = $this->client->deleteImageStream($this->json->clientTest->image_stream);

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete image stream.'
      );
    }
  }

  public function testDeleteSecret() {
    if ($this->json->clientTest->delete) {

      $request = $this->client->deleteSecret($this->json->clientTest->secret->name);

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete secret.'
      );
    }
  }

}
