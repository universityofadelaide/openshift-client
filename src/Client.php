<?php

namespace UniversityOfAdelaide\OpenShift;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

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
      ],
    ],
    'imagestream' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}'
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams'
      ],
      'update' => [
        // PUT replaces the imagestream.
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}'
      ],
    ],
    'buildconfig' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}'
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs'
      ],
      'update' => [
        // PUT replaces the imagestream.
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}'
      ],
    ],
    'service' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/api/v1/namespaces/{namespace}/services'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/services/{name}'
      ],
      'get' => [
        // lists all services.
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/services'
      ],
      'update' => [
        // PUT replaces the service.
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/services/{name}'
      ]
    ],
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

    try {
      $response = $this->guzzleClient->request($method, $uri, $requestOptions);
    } catch (RequestException $exception) {
      // @todo - handel the exception;
      $message = $exception->getMessage();
      var_dump($message);
      die();
    }
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
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

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

    $response = $this->request($resourceMethod['action'], $uri, $secret);

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

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
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

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    if(isset($data['dependencies'])) {
      $dependencies = [
        $data['dependencies']
      ];
    }

    // @todo - use a model.
    $service = [
      'metadata' => [
        'name' => $name,
        'namespace' => $this->namespace,
        'annotations' => [
          'description' => isset($data['description']) ? $data['description'] : '',
          'service.alpha.openshift.io/dependencies' => isset($dependencies) ? $dependencies : '',
        ],
      ],
      'spec' => [
        'ports' => [
          // Defaults to TCP.
          'protocol' => isset($data['protocol']) ? $data['protocol'] : 'TCP',
          'port' => (int) $data['port'],
          'targetPort' => (string) $data['targetPort'],
        ],
        'selector' => '' // is this a string ?
      ]
    ];

    $response = $this->request($resourceMethod['action'], $uri, $service);

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
  public function createBuildConfig($name, $secret, $imagestream, $data) {


    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $buildConfig = [
      'kind' => 'buildConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to build the application',
          'name' => $name . '-bc',
        ],
      ],
      'spec' => [
        'output' => [
          'to' => [
            'kind' => 'ImageStreamTag',
            'name' => $imagestream .'-imagestream:latest'
          ]
        ],
        'source' => [
          'type' => 'Git',
          'git' => [
            'ref' => (string) $data['git']['ref'],
            'uri' => (string) $data['git']['uri'],
          ],
          'secrets' => [
            [
              'destinationDir' => '.',
              'secret' => [
                'name' => $secret
              ],
            ]
          ],
          'sourceSecret' => [
            'name' => $secret
          ],
        ],
        'strategy' => [
          'sourceStrategy' => [
            'from' => [
              'kind' => (string) $data['source']['type'],
              'name' => (string) $data['source']['name']
            ],
            'pullSecret' => [
              'name' => $secret
            ]
          ],
         'type' => 'Source'
        ],
        // @todo - figure out github and other types of triggers
        'triggers' => [],
      'status' => [
        'lastversion' => time()
      ],
    ];

    $response = $this->request($resourceMethod['action'], $uri, $buildConfig);

    if ($response['response'] === 201) {
      return $response['response'];
    }
    else {
      return FALSE;
    }

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
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);

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
  public function createImageStream($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);

    $imageStream = [
      'kind' => 'ImageStream',
      'metadata' => [
        'name' => $name . '-imagestream',
        'annotations' => [
          'description' => 'Keeps track of changes in the application image'
        ]
      ],
      'spec' => [
        'dockerImageRepository' => ''
      ]
    ];

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);

    if ($response['response'] === 201) {
      return $response['response'];
    }
    else {
      return FALSE;
    }


  }

  /**
   * @inheritdoc
   */
  public function updateImageStream($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);

    $imageStream = [
      'kind' => 'ImageStream',
      'metadata' => [
        'name' => $name . '-imagestream',
        'annotations' => [
          'description' => 'Keeps track of changes in the application image'
        ]
      ],
      'spec' => [
        'dockerImageRepository' => ''
      ]
    ];

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);

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
  public function deleteImageStream($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);

    $response = $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);

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
