<?php

namespace UniversityOfAdelaide\OpenShift;

use UniversityOfAdelaide\OpenShift\Model\V1ObjectMeta;
use UniversityOfAdelaide\OpenShift\Model\V1Secret;
use GuzzleHttp\Client as GuzzleClient;

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
   * Current working namespace.
   *
   * @var string
   */
    private $namespace;

  /**
   * Base url to OpenShift.
   *
   * @var string
   */
    private $host;

  /**
   * Guzzle HTTP Client
   *
   * @var \GuzzleHttp\Client
   */
  protected $guzzleClient;

  /**
   * Client constructor.
   *
   * @param string $host The hostname.
   * @param string $token A generated Auth token.
   * @param string $namespace Namespace/project on which to operate methods on.
   * @param bool $devMode Turn debug mode on or off.
   */
    public function __construct($host, $token, $namespace, $devMode = FALSE) {

      $this->host = $host;
      $this->namespace = $namespace;

      $guzzle_options = [
        'verify' => TRUE,
        'base_uri' => $host,
        'headers' => [
          'Authorization' => 'Bearer ' . $token
        ],
      ];

      // If dev mode - turn off SSL Verification.
      if ($devMode) {
        $guzzle_options['verify'] = FALSE;
      }

      $this->guzzleClient = new GuzzleClient($guzzle_options);

    }

    public function getGuzzleClient() {
      return $this->guzzleClient;
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
