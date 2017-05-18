<?php

namespace UniversityOfAdelaide\OpenShift;

use UniversityOfAdelaide\OpenShift\Configuration;
use UniversityOfAdelaide\OpenShift\ApiClient;
use UniversityOfAdelaide\OpenShift\Api\Core_v1Api;
use UniversityOfAdelaide\OpenShift\Api\OapiApi;
use UniversityOfAdelaide\OpenShift\Model\V1ObjectMeta;
use UniversityOfAdelaide\OpenShift\Model\V1Secret;

/**
 * Class Client
 * Provides sugar and helper methods for easier use
 * of the the swagger api(s) for OpenShift.
 *
 * @package UniversityOfAdelaide\OpenShift
 */
class Client
{

  /**
   * Configuration object for clients.
   *
   * @var \UniversityOfAdelaide\OpenShift\Configuration
   */
    private $configuration;

  /**
   * Base api client, injected into core and openshift clients.
   *
   * @var \UniversityOfAdelaide\OpenShift\ApiClient
   */
    private $baseClient;

  /**
   * Base kubernetes api client.
   *
   * @var \UniversityOfAdelaide\OpenShift\Api\Core_v1Api
   */
    private $coreClient;

  /**
   * Openshift /oapi client.
   *
   * @var \UniversityOfAdelaide\OpenShift\Api\OapiApi
   */
    private $openshiftClient;


  /**
   * Current working namespace.
   *
   * @var string
   */
    private $namespace;

  /**
   * Client constructor.
   *
   * @param string $host The hostname.
   * @param string $token A generated Auth token.
   * @param string $namespace Namespace/project on which to operate methods on.
   * @param bool $devMode Turn debug mode on or off.
   */
    public function __construct($host, $token, $namespace, $devMode = FALSE) {
        // Setup configuration entity.
        $this->configuration = new Configuration();
        $this->configuration->setHost($host);
        $this->configuration->setApiKeyPrefix('Token', 'Bearer');
        $this->configuration->setApiKey('Token', $token);
        if ($devMode) {
          $this->configuration->setSSLVerification(FALSE);
          $this->configuration->setDebug(TRUE);
        }
        // There is nothing that retrieves the api key or anything in the api client
        // @todo - this sucks .. why is this the case. Force set the header for now.
        $this->configuration->addDefaultHeader('Authorization',  $this->configuration->getApiKeyPrefix('Token') . ' ' . $this->configuration->getApiKey('Token'));

        // Store the current working namespace.
        $this->namespace = $namespace;

        // Configure swagger generated clients.
        $this->baseClient = new ApiClient($this->configuration);
        $this->coreClient = new Core_v1Api($this->baseClient);
        $this->openshiftClient = new OapiApi($this->baseClient);
    }

  /**
   * Create a namespaced secret.
   *
   * @param string $name Name of the secret.
   * @param array $data Key Value array of secret data. This will base64
   * encoded.
   * @return \UniversityOfAdelaide\OpenShift\Model\V1Secret
   */
    public function createSecret($name, array $data) {

      // base64 the data
      foreach ($data as $key => $value) {
        $data[$key] = base64_encode($value);
      }

      $secret = new V1Secret([
        'api_version' => 'v1',
        'kind' => 'Secret',
        'metadata' => new V1ObjectMeta([
          'name' => $name
        ]),
        'type' => 'Opaque',
        'data' => $data
      ]);

      return $this->coreClient->createCoreV1NamespacedSecret($this->namespace, $secret, TRUE);
    }

}
