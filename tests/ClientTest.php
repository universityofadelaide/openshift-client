<?php

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase {

  private $host;
  private $token;
  private $namespace;

  public function setUp() {
    global $argv, $argc;

    $this->assertEquals(5, $argc, 'Missing arguments');
    $this->host = $argv[2];
    $this->token = $argv[3];
    $this->namespace = $argv[4];
  }

  public function testGetGuzzleClient() {
    // Test creating the client
    $this->assertInstanceOf(
      GuzzleClient::class,
      (new Client($this->host, $this->token, $this->namespace, TRUE))->getGuzzleClient(),
      'Unable to create Guzzle client.'
    );
  }

  public function testCreateSecret() {
    $client = new Client($this->host, $this->token, $this->namespace, TRUE);

    $this->assertEquals(
      201,
      $client->createSecret('piedtest', [
        'username' => 'pied-piper',
        'password' => 'testpass',
      ]),
      'Unable to create test user.'
    );
  }

  public function testDeleteSecret() {
    $client = new Client($this->host, $this->token, $this->namespace, TRUE);

    $this->assertEquals(
      200,
      $client->deleteSecret('piedtest'),
      'Unable to delete test user.'
    );
  }
}
