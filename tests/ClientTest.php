<?php

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase {

  private $host;
  private $token;
  private $namespace;
  private $yaml;

  protected $client;

  public function setUp() {
    global $argv, $argc;

    $this->assertEquals(6, $argc, 'Missing arguments');
    if (file_exists($argv[5])) {
      $this->host = $argv[2];
      $this->token = $argv[3];
      $this->namespace = $argv[4];
      $this->yaml = json_decode(file_get_contents($argv[5]));
    }
    else {
      die("Unable to open specified file $argv[2]");
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
      $this->client->createImageStream('pied-stream'),
      'Unable to create image stream.'
    );
  }

  public function testCreateBuildConfig() {
    $data = [
      'git' => [
        'uri' => $this->yaml['clientTest']['source']['git']['uri'],
        'ref' => $this->yaml['clientTest']['source']['git']['ref'],
      ],
      'source' => [
        'type' => $this->yaml['clientTest']['sourceStrategy']['from']['kind'],
        'name' => $this->yaml['clientTest']['sourceStrategy']['from']['name'],
      ],
    ];
    $this->assertEquals(
      201,
      $this->client->createBuildConfig('pied-build', 'pied-pass', 'pied-dreams', $data),
      'Unable to create build config.'
    );
  }

  public function testCreateDeploymentConfig() {
    $data = [];
    $this->assertEquals(
      201,
      $this->client->createDeploymentConfig('pied-build', '', '', $data),
      'Unable to create build config.'
    );
  }

  public function testDeleteDeploymentConfig() {
    $this->assertEquals(
      200,
      $this->client->deleteDeploymentConfig('pied-build'),
      'Unable to delete deploy config.'
    );
  }

  public function testDeleteBuildConfig() {
    $this->assertEquals(
      200,
      $this->client->deleteBuildConfig('pied-build'),
      'Unable to delete build config.'
    );
  }

  public function testDeleteImageStream() {
    $this->assertEquals(
      200,
      $this->client->deleteImageStream('pied-stream'),
      'Unable to delete image stream.'
    );
  }

  public function testDeleteSecret() {
    $this->assertEquals(
      200,
      $this->client->deleteSecret('pied-pass'),
      'Unable to delete secret.'
    );
  }

}
