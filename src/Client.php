<?php

namespace UniversityOfAdelaide\OpenShift;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 *
 * Provides a client using guzzle to interact easily
 * with the OpenShift api.
 *
 * @package UniversityOfAdelaide\OpenShift
 */
class Client
{

  /**
   * Api version.
   *
   * @var string
   */
  private $apiVersion = 'v1';

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
          'Authorization' => 'Bearer ' . $token,
          // @todo - make this configurable.
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
        ],

      ];

      // If dev mode - turn off SSL Verification.
      if ($devMode) {
        $guzzle_options['verify'] = FALSE;
      }

      $this->guzzleClient = new GuzzleClient($guzzle_options);

    }

  /**
   * Returns the guzzle client.
   *
   * @return \GuzzleHttp\Client
   */
    public function getGuzzleClient() {
      return $this->guzzleClient;
    }

  /**
   * Sends a post request via the guzzle http client.
   *
   * @param string $path
   * @param array $body
   * @return array Returns the status code and json_decoded body contents.
   */
    protected function post($path, $body) {
      $request = $this->guzzleClient->request('POST', $path, [
        'body' => json_encode($body)
      ]);

      // @todo - handle exceptions.

      return [
        'response' => $request->getStatusCode(),
        'body' => json_decode($request->getBody()->getContents())
      ];
    }

  /**
   * Sends a delete request via the guzzle http client.
   *
   * @param string $path
   *
   * @return array Returns the status code and json_decoded body contents.
   */
  protected function delete($path) {
    $request = $this->guzzleClient->request('DELETE', $path, []);

    // @todo - handle exceptions.

    return [
      'response' => $request->getStatusCode(),
      'body'     => json_decode($request->getBody()->getContents()),
    ];
  }

  /**
   * Creates secret on current namespace.
   *
   * @param string $name The key/name of the secret.
   * @param array $data Key, value array data.
   * @return bool|mixed Returns the body response if successful otherwise false
   * if request fails to get back a 201.
   */
    public function createSecret($name, array $data) {

      $path = '/api/' . $this->apiVersion . '/namespaces/' . $this->namespace . '/secrets';

      // base64 the data
      foreach ($data as $key => $value) {
        $data[$key] = base64_encode($value);
      }

      // @todo - this should use  model.
      $secret = [
        'api_version' => 'v1',
        'kind' => 'Secret',
        'metadata' => [
          'name' => $name
        ],
        'type' => 'Opaque',
        'data' => $data
      ];

      $response = $this->post($path, $secret);

      if ($response['response'] === 201) {
        return $response['response'];
      } else {
        // something failed.
        return FALSE;
      }

  }

  public function deleteSecret($name) {
    $path = '/api/' . $this->apiVersion . '/namespaces/' . $this->namespace . '/secrets/' . $name;

    $response = $this->delete($path);

    if ($response['response'] === 200) {
      return $response['response'];
    }
    else {
      return FALSE;
    }
  }
}
