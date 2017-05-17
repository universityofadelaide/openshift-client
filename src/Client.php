<?php

namespace UniversityOfAdelaide\OpenShift;

use UniversityOfAdelaide\OpenShift\Configuration;
use UniversityOfAdelaide\OpenShift\ApiClient;
use UniversityOfAdelaide\OpenShift\Api\Core_v1Api;
use UniversityOfAdelaide\OpenShift\Api\OapiApi;

class Client
{

    private $configuration;
    private $baseClient;
    private $coreClient;
    private $openshiftClient;

    public function __construct($host, $token, $devMode = false) {
        // Setup configuration entity.
        $this->configuration = new Configuration();
        $this->configuration->setHost($host);
        $this->configuration->setApiKeyPrefix('Token', 'Bearer');
        $this->configuration->setApiKey('Token', $token);
        if ($devMode) {
            $this->configuration->setSSLVerification(FALSE);
            $this->configuration->setDebug(TRUE);
        }

        // Configure swagger generated clients.
        $this->baseClient = new ApiClient($this->configuration);
        $this->coreClient = new Core_v1Api($this->baseClient);
        $this->openshiftClient = new OapiApi($this->baseClient);
    }

}
