<?php

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Client;
use GuzzleHttp\Client as GuzzleClient;
use UniversityOfAdelaide\OpenShift\Objects\Route;

class ClientTest extends TestCase {

  private $host;
  private $token;
  private $namespace;
  private $json;

  private $volumes;

  /**
   * Protected client variable.
   *
   * @var \UniversityOfAdelaide\OpenShift\ClientInterface
   */
  protected $client;

  /**
   * Setup things required for the tests.
   */
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

    $this->volumes = [
      [
        'type' => 'pvc',
        'name' => $this->json->clientTest->artifacts . '-public',
        'path' => '/web/sites/default/files',
      ],
      [
        'type' => 'pvc',
        'name' => $this->json->clientTest->artifacts . '-private',
        'path' => '/web/private',
      ],
    ];
  }

  /**
   * Setup the guzzle client for testing.
   */
  public function testGetGuzzleClient() {
    // Test creating the client.
    $this->assertInstanceOf(
      GuzzleClient::class,
      $this->client->getGuzzleClient(),
      'Unable to create Guzzle client.'
    );
  }

  /**
   * Test secret creation.
   */
  public function testCreateSecret() {
    $response = $this->client->createSecret($this->json->clientTest->testSecret->name, [
      'username' => $this->json->clientTest->testSecret->user,
      'password' => $this->json->clientTest->testSecret->pass,
    ]);

    $this->assertNotFalse(
      $response,
      'Unable to create secret - ' . print_r($response, TRUE)
    );
  }

  /**
   * Test updating a secret.
   */
  public function testUpdateSecret() {

    $response = $this->client->updateSecret($this->json->clientTest->testSecret->name, [
      'username' => $this->json->clientTest->testSecret->user,
      'password' => $this->json->clientTest->testSecret->alt_pass,
    ]);

    $this->assertNotFalse(
      $response,
      'Unable to update secret.'
    );
  }

  /**
   * Test retrieving a secret.
   */
  public function testGetSecret() {
    $response = $this->client->getSecret($this->json->clientTest->testSecret->name);

    $this->assertNotFalse(
      $response,
      'Unable to request secret.'
    );
  }

  /**
   * Test creating an image stream.
   */
  public function testCreateImageStream() {
    $response = $this->client->createImageStream($this->json->clientTest->artifacts . '-stream');

    $this->assertNotFalse(
      $response,
      'Unable to create image stream.'
    );
  }

  /**
   * Test retrieving an image stream.
   */
  public function testGetImageStream() {
    $response = $this->client->getImageStream($this->json->clientTest->artifacts . '-stream');

    $this->assertNotFalse(
      $response,
      'Unable to retrieve image stream.'
    );

    $this->assertInternalType(
      'array',
      $response,
      'Returned type for image stream incorrect.'
    );

  }

  /**
   * Test creating a persistent volume claim.
   */
  public function testCreatePersistentVolumeClaim1() {

    $response = $this->client->createPersistentVolumeClaim(
      $this->json->clientTest->artifacts . '-private',
      'ReadWriteMany',
      '10Gi'
    );

    $this->assertNotFalse(
      $response,
      'Unable to create persistent volume claim.'
    );
  }

  /**
   * Test creating a second persistent volume claim.
   */
  public function testCreatePersistentVolumeClaim2() {
    $response = $this->client->createPersistentVolumeClaim(
      $this->json->clientTest->artifacts . '-public',
      'ReadWriteMany',
      '10Gi'
    );

    $this->assertNotFalse(
      $response,
      'Unable to create persistent volume claim.'
    );
  }

  /**
   * Test creating a build config.
   */
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

    $response = $this->client->createBuildConfig(
      $this->json->clientTest->artifacts . '-build',
      $this->json->clientTest->buildSecret,
      $this->json->clientTest->artifacts . '-stream:master',
      $data
    );

    $this->assertNotFalse(
      $response,
      'Unable to create build config.'
    );
  }

  /**
   * Test retrieving a build config.
   */
  public function testGetBuildConfig() {
    $response = $this->client->getBuildConfig($this->json->clientTest->artifacts . '-build');

    $this->assertNotFalse(
      $response,
      'Unable to retrieve build config.'
    );

    $this->assertInternalType(
      'array',
      $response
    );
  }

  /**
   * Test retrieving an image stream tag.
   */
  public function getImageStreamTag() {
    $response = $this->client->getImageStreamTag($this->json->clientTest->artifacts . '-stream:master');

    $this->assertNotFalse(
      $response,
      'Unable to retrieve image stream tag'
    );
  }

  /**
   * Test creating a deployment config.
   */
  public function testCreateDeploymentConfig() {
    $deploy_env_vars = [];
    foreach ($this->json->clientTest->envVars as $env_var) {
      $deploy_env_vars[] = [
        'name' => $env_var->name,
        'value' => $env_var->value,
      ];
    }

    $data = [
      'containerPort' => 8080,
      'memory_limit' => '128Mi',
      'env_vars' => $deploy_env_vars,
      'annotations' => [
        'test' => 'tester',
      ],
    ];

    $name = $this->json->clientTest->artifacts . '-deploy';
    $image_stream_tag = $this->json->clientTest->artifacts . '-stream:master';
    $image_name = $this->json->clientTest->artifacts . '-image';

    $response = $this->client->createDeploymentConfig(
      $name,
      $image_stream_tag,
      $image_name,
      $this->volumes,
      $data
    );

    $this->assertNotFalse(
      $response,
      'Unable to create deployment config.'
    );
  }

  /**
   * Test creation of a cron job task.
   */
  public function testCreateCronJob() {
    $deploy_env_vars = [];
    foreach ($this->json->clientTest->envVars as $env_var) {
      $deploy_env_vars[] = [
        'name' => $env_var->name,
        'value' => $env_var->value,
      ];
    }

    $data = [
      'memory_limit' => '128Mi',
      'env_vars' => $deploy_env_vars,
      'annotations' => [
        'test' => 'tester',
      ],
    ];

    $name = $this->json->clientTest->artifacts . '-cron';
    $image_name = $this->json->clientTest->artifacts . '-image';

    $args = [
      '/bin/sh',
      '-c',
      'cd /code; drush -r web cron',
    ];

    $response = $this->client->createCronJob(
      $name,
      $image_name,
      '*/30 * * * *',
      $args,
      $this->volumes,
      $data
    );

    $this->assertNotFalse(
      $response,
      'Unable to create cron job config.'
    );
  }

  /**
   * Test retrieving the deployment config.
   */
  public function testGetDeploymentConfig() {
    $response = $this->client->getDeploymentConfig($this->json->clientTest->artifacts . '-deploy');

    $this->assertNotFalse(
      $response,
      'Unable to retrieve deploy config.'
    );

    $this->assertInternalType(
      'array',
      $response
    );
  }

  /**
   * Test creating a service.
   */
  public function testCreateService() {
    $data = [
      'dependencies' => '',
      'description' => $this->json->clientTest->artifacts . '-description',
      'protocol' => 'TCP',
      'port' => 8080,
      'targetPort' => 8080,
      'deployment' => $this->json->clientTest->artifacts . '-deploy',
    ];

    $name = $this->json->clientTest->artifacts . '-service';

    $response = $this->client->createService($name, $data);

    $this->assertNotFalse(
      $response,
      'Unable to create service.'
    );
  }

  /**
   * Test creating a route for the service.
   */
  public function testCreateRoute() {
    $name = $this->json->clientTest->artifacts . '-route';
    $service = $this->json->clientTest->artifacts . '-service';
    $application_domain = $this->json->clientTest->domain;

    /** @var \UniversityOfAdelaide\OpenShift\Objects\Route $route */
    $route = Route::create()
      ->setName($name)
      ->setHost($application_domain)
      ->setInsecureEdgeTerminationPolicy('Allow')
      ->setTermination('edge')
      ->setToKind('Service')
      ->setToName($service)
      ->setToWeight(50)
      ->setWildcardPolicy('None');

    $response = $this->client->createRoute($route);

    $this->assertNotFalse(
      $response,
      'Unable to create service.'
    );
  }

  /**
   * Test deleting the route.
   */
  public function testDeleteRoute() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteRoute($this->json->clientTest->artifacts . '-route');

      $this->assertNotFalse(
        $response,
        'Unable to delete route.'
      );
    }
  }

  /**
   * Test deleting the service.
   */
  public function testDeleteService() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteService($this->json->clientTest->artifacts . '-service');

      $this->assertNotFalse(
        $response,
        'Unable to delete route.'
      );
    }
  }

  /**
   * Test deleting the cronjob.
   */
  public function testDeleteCronJob() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteCronJob($this->json->clientTest->artifacts . '-cron');

      $this->assertNotFalse(
        $response,
        'Unable to delete cronjob config.'
      );
    }
  }

  /**
   * Test deleting the deployment configuration.
   */
  public function testDeleteDeploymentConfig() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteDeploymentConfig($this->json->clientTest->artifacts . '-deploy');

      $this->assertNotFalse(
        $response,
        'Unable to delete deploy config.'
      );
    }
  }

  /**
   * Test deleting the build configuration.
   */
  public function testDeleteBuildConfig() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteBuildConfig($this->json->clientTest->artifacts . '-build');

      $this->assertNotFalse(
        $response,
        'Unable to delete build config.'
      );
    }
  }

  /**
   * Test deleting the persistent volume claim.
   */
  public function testDeletePersistentVolumeClaim1() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deletePersistentVolumeClaim($this->json->clientTest->artifacts . '-private');

      $this->assertNotFalse(
        $response,
        'Unable to delete persistent volume claim.'
      );
    }
  }

  /**
   * Test deleting the persistent volume claim.
   */
  public function testDeletePersistentVolumeClaim2() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deletePersistentVolumeClaim($this->json->clientTest->artifacts . '-public');

      $this->assertNotFalse(
        $response,
        'Unable to delete persistent volume claim.'
      );
    }
  }

  /**
   * Test deleting the image stream.
   */
  public function testDeleteImageStream() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteImageStream($this->json->clientTest->artifacts . '-stream');

      $this->assertNotFalse(
        $response,
        'Unable to delete image stream.'
      );
    }
  }

  /**
   * Test deleteing the secret.
   */
  public function testDeleteSecret() {
    if ($this->json->clientTest->delete) {
      $response = $this->client->deleteSecret($this->json->clientTest->testSecret->name);

      $this->assertNotFalse(
        $response,
        'Unable to delete secret.'
      );
    }
  }

}
