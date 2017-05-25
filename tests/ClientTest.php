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

    $request = $this->client->createSecret($this->json->clientTest->testSecret->name, [
      'username' => $this->json->clientTest->testSecret->user,
      'password' => $this->json->clientTest->testSecret->pass,
    ]);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create secret.'
    );
  }

  public function testUpdateSecret() {

    $request = $this->client->updateSecret($this->json->clientTest->testSecret->name, [
      'username' => $this->json->clientTest->testSecret->user,
      'password' => $this->json->clientTest->testSecret->alt_pass,
    ]);

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to update secret.'
    );
  }

  public function testGetSecret() {

    $request = $this->client->getSecret($this->json->clientTest->testSecret->name);

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to request secret.'
    );
  }

  public function testCreateImageStream() {

    $request = $this->client->createImageStream($this->json->clientTest->artifacts . '-stream');

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create image stream.'
    );
  }

  public function testGetImageStream() {
    $request = $this->client->getImageStream($this->json->clientTest->artifacts . '-stream');

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to retrieve image stream.'
    );

    $this->assertObjectHasAttribute(
      'items',
      $request['body']
    );

  }

  public function testCreatePersistentVolumeClaim1() {

    $request = $this->client->createPersistentVolumeClaim(
      $this->json->clientTest->artifacts . '-private',
      'ReadWriteMany',
      '10Gi'
    );

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create persistent volume claim.'
    );
  }

  public function testCreatePersistentVolumeClaim2() {

    $request = $this->client->createPersistentVolumeClaim(
       $this->json->clientTest->artifacts . '-public',
      'ReadWriteMany',
      '10Gi'
    );

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create persistent volume claim.'
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

    $request = $this->client->createBuildConfig(
      $this->json->clientTest->artifacts . '-build',
      $this->json->clientTest->buildSecret,
      $this->json->clientTest->artifacts . '-stream',
      $data
    );

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create build config.'
    );
  }

  public function testGetBuildConfig() {
    $request = $this->client->getBuildConfig($this->json->clientTest->artifacts . '-build');

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to retrieve build config.'
    );

    $this->assertObjectHasAttribute(
      'items',
      $request['body']
    );

  }

  public function testCreateDeploymentConfig() {
    $data = [
      'containerPort' => 8080,
      'memory_limit' => '128Mi',
      'env_vars' => $this->json->clientTest->envVars,
      'public_volume' => $this->json->clientTest->artifacts . '-public',
      'private_volume' => $this->json->clientTest->artifacts . '-private',
    ];

    $name = $this->json->clientTest->artifacts . '-deploy';
    $image_stream_tag = $this->json->clientTest->artifacts . '-stream';
    $image_name = $this->json->clientTest->artifacts . '-image';

    $request = $this->client->createDeploymentConfig(
      $name,
      $image_stream_tag,
      $image_name,
      $data
    );

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create deployment config.'
    );
  }


  public function testGetDeploymentConfig() {
    $request = $this->client->getDeploymentConfig($this->json->clientTest->artifacts . '-deploy');

    $this->assertEquals(
      200,
      $request['response'],
      'Unable to retrieve deploy config.'
    );

    $this->assertObjectHasAttribute(
      'items',
      $request['body']
    );

  }

  public function testCreateService() {
    $data = [
      'dependencies' => '',
      'description' => $this->json->clientTest->artifacts . '-description',
      'protocol' => 'TCP',
      'port' => 8080,
      'targetPort' => 8080,
      'deployment' => $this->json->clientTest->artifacts . '-deploy'
    ];

    $name = $this->json->clientTest->artifacts . '-service';

    $request = $this->client->createService($name, $data);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create service.'
    );

  }

  public function testCreateRoute() {
    $name = $this->json->clientTest->artifacts . '-route';
    $service = $this->json->clientTest->artifacts . '-service';
    $application_domain = $this->json->clientTest->domain;

    $request = $this->client->createRoute($name, $service, $application_domain);

    $this->assertEquals(
      201,
      $request['response'],
      'Unable to create service.'
    );

  }

  public function testDeleteRoute() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deleteRoute($this->json->clientTest->artifacts . '-route');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete route.'
      );
    }
  }

  public function testDeleteService() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deleteService($this->json->clientTest->artifacts . '-service');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete route.'
      );
    }
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

  public function testDeletePersistentVolumeClaim1() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deletePersistentVolumeClaim($this->json->clientTest->artifacts . '-private');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete persistent volume claim.'
      );
    }
  }

  public function testDeletePersistentVolumeClaim2() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deletePersistentVolumeClaim($this->json->clientTest->artifacts . '-public');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete persistent volume claim.'
      );
    }
  }

  public function testDeleteImageStream() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deleteImageStream($this->json->clientTest->artifacts . '-stream');

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete image stream.'
      );
    }
  }

  public function testDeleteSecret() {
    if ($this->json->clientTest->delete) {
      $request = $this->client->deleteSecret($this->json->clientTest->testSecret->name);

      $this->assertEquals(
        200,
        $request['response'],
        'Unable to delete secret.'
      );
    }
  }

}
