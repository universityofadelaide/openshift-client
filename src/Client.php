<?php

namespace UniversityOfAdelaide\OpenShift;

use UniversityOfAdelaide\OpenShift\Configuration;
use UniversityOfAdelaide\OpenShift\ApiClient;
use UniversityOfAdelaide\OpenShift\Api\Core_v1Api;
use UniversityOfAdelaide\OpenShift\Api\OapiApi;

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

        // Store the current working namespace.
        $this->namespace = $namespace;

        // Configure swagger generated clients.
        $this->baseClient = new ApiClient($this->configuration);
        $this->coreClient = new Core_v1Api($this->baseClient);
        $this->openshiftClient = new OapiApi($this->baseClient);
    }

}
