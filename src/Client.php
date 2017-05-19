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
class Client implements OpenShiftClientInterface
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
   * @inheritdoc
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

  /**
   * @inheritdoc
   */
  public function getSecret($name) {
    // TODO: Implement getSecret() method.
  }

  /**
   * @inheritdoc
   */
  public function updateSecret($name, array $data) {
    // TODO: Implement updateSecret() method.
  }

  /**
   * @inheritdoc
   */
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

  /**
   * @inheritdoc
   */
  public function getService($name) {
    // TODO: Implement getService() method.
  }

  /**
   * @inheritdoc
   */
  public function createService($name, array $data) {
    // TODO: Implement createService() method.
  }

  /**
   * @inheritdoc
   */
  public function updateService() {
    // TODO: Implement updateService() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteService($name) {
    // TODO: Implement deleteService() method.
  }

  /**
   * @inheritdoc
   */
  public function getRoute() {
    // TODO: Implement getRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function createRoute() {
    // TODO: Implement createRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function updateRoute() {
    // TODO: Implement updateRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteRoute() {
    // TODO: Implement deleteRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function getBuildConfig() {
    // TODO: Implement getBuildConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function createBuildConfig() {
    // TODO: Implement createBuildConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function updateBuildConfig() {
    // TODO: Implement updateBuildConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteBuildConfig() {
    // TODO: Implement deleteBuildConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function getImageStream() {
    // TODO: Implement getImageStream() method.
  }

  /**
   * @inheritdoc
   */
  public function createImageStream() {
    // TODO: Implement createImageStream() method.
  }

  /**
   * @inheritdoc
   */
  public function updateImageStream() {
    // TODO: Implement updateImageStream() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteImageStream() {
    // TODO: Implement deleteImageStream() method.
  }

  /**
   * @inheritdoc
   */
  public function getImageStreamTag() {
    // TODO: Implement getImageStreamTag() method.
  }

  /**
   * @inheritdoc
   */
  public function createImageSteamTag() {
    // TODO: Implement createImageSteamTag() method.
  }

  /**
   * @inheritdoc
   */
  public function updateImageSteamTag() {
    // TODO: Implement updateImageSteamTag() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteImageSteamTag() {
    // TODO: Implement deleteImageSteamTag() method.
  }

  /**
   * @inheritdoc
   */
  public function getPersistentVolumeClaim() {
    // TODO: Implement getPersistentVolumeClaim() method.
  }

  /**
   * @inheritdoc
   */
  public function createPersistentVolumeClaim() {
    // TODO: Implement createPersistentVolumeClaim() method.
  }

  /**
   * @inheritdoc
   */
  public function updatePersistentVolumeClaim() {
    // TODO: Implement updatePersistentVolumeClaim() method.
  }

  /**
   * @inheritdoc
   */
  public function deletePersistentVolumeClaim() {
    // TODO: Implement deletePersistentVolumeClaim() method.
  }

  /**
   * @inheritdoc
   */
  public function getDeploymentConfig() {
    // TODO: Implement getDeploymentConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function createDeploymentConfig() {
    // TODO: Implement createDeploymentConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function updateDeploymentConfig() {
    // TODO: Implement updateDeploymentConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteDeploymentConfig() {
    // TODO: Implement deleteDeploymentConfig() method.
  }
}
