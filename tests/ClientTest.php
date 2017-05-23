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
    $this->assertEquals(
      201,
      $this->client->createSecret('pied-pass', [
        'username' => 'pied-piper',
        'password' => 'This guy..',
      ]),
      'Unable to create secret.'
    );
  }

  public function testUpdateSecret() {
    $this->assertEquals(
      200,
      $this->client->updateSecret('pied-pass', [
        'username' => 'pied-piper',
        'password' => 'middleout',
      ]),
      'Unable to update secret.'
    );
  }

  public function testCreateImageStream() {
    $this->assertEquals(
      201,
      $this->client->createImageStream($this->json->clientTest->image_stream),
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
    $this->assertEquals(
      201,
      $this->client->createBuildConfig('pied-build', 'pied-pass', $this->json->clientTest->image_stream, $data),
      'Unable to create build config.'
    );
  }

  public function testCreateDeploymentConfig() {
    $data = [
      'containerPort' => 8080,
      'memory_limit' => 128,
      'env_vars' => $this->json->clientTest->envVars,
    ];
    $name = 'pied-deploy';
    $image_stream_tag = $this->json->clientTest->image_stream;
    $image_name = $this->json->clientTest->image_name;
    $this->assertEquals(
      201,
      $this->client->createDeploymentConfig($name, $image_stream_tag, $image_name, $data),
      'Unable to create deployment config.'
    );
  }

  public function testDeleteDeploymentConfig() {
    if ($this->json->clientTest->delete) {
      $this->assertEquals(
        200,
        $this->client->deleteDeploymentConfig('pied-deploy'),
        'Unable to delete deploy config.'
      );
    }
  }

  public function testDeleteBuildConfig() {
    if ($this->json->clientTest->delete) {
      $this->assertEquals(
        200,
        $this->client->deleteBuildConfig('pied-build'),
        'Unable to delete build config.'
      );
    }
  }

  public function testDeleteImageStream() {
    if ($this->json->clientTest->delete) {
      $this->assertEquals(
        200,
        $this->client->deleteImageStream($this->json->clientTest->image_stream),
        'Unable to delete image stream.'
      );
    }
  }

  public function testDeleteSecret() {
    if ($this->json->clientTest->delete) {
      $this->assertEquals(
        200,
        $this->client->deleteSecret('pied-pass'),
        'Unable to delete secret.'
      );
    }
  }

}
