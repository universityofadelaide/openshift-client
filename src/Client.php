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
   * Resource map.
   *
   * @var array
   */
  protected $resourceMap = [
    'secret' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/api/v1/namespaces/{namespace}/secrets',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}'
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/secrets'
      ],
      'update' => [
        // PUT replaces the entire secret.
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}'
      ]
    ]
  ];

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
   * Returns the api version.
   *
   * @return string
   */
   public function getApiVersion() {
      return $this->apiVersion;
   }

  /**
   * Set the api version number.
   *
   * @param string $apiVersion Api version number.
   */
   public function setApiVersion($apiVersion) {
      $this->apiVersion = (string) $apiVersion;
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
   * Sends a request via the guzzle http client
   *
   * @param string $method HTTP VERB
   * @param string $uri Path the endpoint
   * @param array $body Request body to be converted to JSON.
   * @param array $query Query params
   *
   * @return array Returns the status code and json_decoded body contents.
   */
  protected function request($method, $uri, array $body = [], array $query = []) {
    $requestOptions = [];

    if ($method != 'DELETE') {
      $requestOptions = [
        'query' => is_array($query) ? $query : [],
        'body'  => is_array($body) ? json_encode($body) : $body,
      ];
    }

    $response = $this->guzzleClient->request($method, $uri, $requestOptions);

    return [
      'response' => $response->getStatusCode(),
      'body' => json_decode($response->getBody()->getContents())
    ];
  }

  /**
   * Gets the uri and action from the resourceMap from the class Method.
   *
   * @param string $method The class method name.
   * @return array
   */
  protected function getResourceMethod($method) {
    $name = explode('::', $method);
    $nameArray =  preg_split('/(?=[A-Z])/', end($name));
    // the first element is the action
    $action = array_shift($nameArray);
    $method = strtolower(implode('', $nameArray));
    return $this->resourceMap[$method][$action];
  }

  /**
   * Creates a relative request url.
   *
   * @param $uri
   * @param array $params Params that map to the uri resource path e.g
   * /api/{namespace}/{name}
   *
   * @return string The request uri.
   */
  protected function createRequestUri($uri, array $params = []) {

    // By default replace the {namespace} this is set in configuration.
    if ($this->namespace !== null) {
      $uri = str_replace('{' . 'namespace' . '}', $this->namespace, $uri);
    }

    foreach ($params as $key => $param) {
      // perform a string replace on the uri.
      $uri = str_replace('{' . $key . '}', $param, $uri);
    }

    return $uri;
  }

  /**
   * @inheritdoc
   */
    public function createSecret($name, array $data) {

      $method = __METHOD__;
      $resourceMethod = $this->getResourceMethod($method);

      // base64 the data
      foreach ($data as $key => $value) {
        $data[$key] = base64_encode($value);
      }

      // @todo - this should use model.
      $secret = [
        'api_version' => 'v1',
        'kind' => 'Secret',
        'metadata' => [
          'name' => $name
        ],
        'type' => 'Opaque',
        'data' => $data
      ];

      $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $secret);

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

    $resourceMethod = $this->getResourceMethod(__METHOD__);

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), []);

    return $response['response'];
  }

  /**
   * @inheritdoc
   */
  public function updateSecret($name, array $data) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);

    // base64 the data
    foreach ($data as $key => $value) {
      $data[$key] = base64_encode($value);
    }

    $secret = [
      'api_version' => 'v1',
      'kind' => 'Secret',
      'metadata' => [
        'name' => $name
      ],
      'type' => 'Opaque',
      'data' => $data
    ];

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $secret);

    if ($response['response'] === 200) {
      return $response['response'];
    } else {
      // something failed.
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function deleteSecret($name) {

    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

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
