<?php

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase {

  private $host;
  private $token;
  private $namespace;

  protected $client;

  public function setUp() {
    global $argv, $argc;

    $this->assertEquals(5, $argc, 'Missing arguments');
    $this->host = $argv[2];
    $this->token = $argv[3];
    $this->namespace = $argv[4];

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
      $this->client->createSecret('piedtest', [
        'username' => 'pied-piper',
        'password' => 'testpass',
      ]),
      'Unable to create secret.'
    );
  }

  public function testUpdateSecret() {
    $this->assertEquals(
      200,
      $this->client->updateSecret('piedtest', [
        'username' => 'pied-piper',
        'password' => 'middleout',
      ]),
      'Unable to update secret.'
    );
  }


  public function testDeleteSecret() {
    $this->assertEquals(
      200,
      $this->client->deleteSecret('piedtest'),
      'Unable to delete secret.'
    );
  }

  public function testCreateImageStream() {
    $this->assertEquals(
      201,
      $this->client->createImageStream('dreams'),
      'Unable to create image stream.'
    );
  }

  public function deleteImageStream() {
    $this->assertEquals(
      200,
      $this->client->deleteImageStream('dreams'),
      'Unable to delete image stream.'
    );
  }
}
