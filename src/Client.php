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
class Client implements OpenShiftClientInterface {

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
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}'
      ],
    ],
    'deploymentconfig' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}'
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs'
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}'
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
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/services/{name}'
      ]
    ],
    'route' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes/{name}'
      ],
      'get' => [
        // lists all routes.
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes'
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes/{name}'
      ]
    ],
    'persistentvolumeclaim' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims'
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}'
      ],
      'get' => [
        // lists all persistentvolumeclaims.
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims'
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}'
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
        'body' => is_array($body) ? json_encode($body) : $body,
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
    $nameArray = preg_split('/(?=[A-Z])/', end($name));
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
    if ($this->namespace !== NULL) {
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
      return $response;
    }
    else {
      // something failed.
      return FALSE;
    }

  }

  /**
   * @inheritdoc
   */
  public function getSecret($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri, []);

    return $response;
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
      return $response;
    }
    else {
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
      return $response;
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

    if (isset($data['dependencies'])) {
      // @todo - json_encode this.
      $dependencies = json_encode([
        $data['dependencies']
      ]);
    }

    // @todo - use a model.
    $service = [
      'kind'     => 'Service',
      'metadata' => [
        'name'        => (string) $name,
        //'namespace'   => $this->namespace,
        'annotations' => [
          'description' => isset($data['description']) ? $data['description'] : '',
          //'service.alpha.openshift.io/dependencies' => isset($dependencies) ? $dependencies : '',
        ],
      ],
      'spec' => [
        // This may be an array.
        'ports' => [
          // Defaults to TCP.
          [
            'name'       => 'web',
            //'protocol'   => isset($data['protocol']) ? $data['protocol'] : 'TCP',
            'port'       => (int) $data['port'],
            'targetPort' => (int) $data['targetPort'],
          ],
        ],
        'selector' => [
          'name' => $data['deployment'],
        ],
      ],
    ];

    $response = $this->request($resourceMethod['action'], $uri, $service);

    if ($response['response'] === 201) {
      return $response;
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
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function getRoute($name) {
    // TODO: Implement getRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function createRoute($name, $service_name, $application_domain) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $route = [
      'kind' => 'Route',
      'metadata' => [
        'name' => (string) $name,
      ],
      'spec' => [
        'host' => (string) $application_domain,
        'to' => [
          'kind' => 'Service',
          'name' => (string) $service_name
        ]
      ],
      // Unsure if required. @see : https://docs.openshift.org/latest/rest_api/openshift_v1.html#v1-routestatus
      'status' => [
        'ingress' => []
      ]
    ];

    $response = $this->request($resourceMethod['action'], $uri, $route);

    if ($response['response'] === 201) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function updateRoute($name, $service_name, $application_domain) {
    // TODO: Implement updateRoute() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteRoute($name) {
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function getBuildConfig($name) {
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function createBuildConfig($name, $secret, $imagestream, $data) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $buildConfig = [
      'kind' => 'BuildConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to build the application',
        ],
        'name' => $name,
      ],
      'spec' => [
        'output' => [
          'to' => [
            'kind' => 'ImageStreamTag',
            'name' => $imagestream . ':latest'
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
        'triggers' => [
          [
            'type' => 'ImageChange',
          ],
          [
            'type' => 'ConfigChange'
          ],
        ],
        'status' => [
          'lastversion' => time()
        ],
      ],
    ];

    $response = $this->request($resourceMethod['action'], $uri, $buildConfig);

    if ($response['response'] === 201) {
      return $response;
    }
    else {
      return FALSE;
    }

  }

  /**
   * @inheritdoc
   */
  public function updateBuildConfig($name, $secret, $imagestream, $data) {
    // TODO: Implement updateBuildConfig() method.
  }

  /**
   * @inheritdoc
   */
  public function deleteBuildConfig($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function getImageStream($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
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
        'name' => $name,
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
      return $response;
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
        'name' => $name,
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
      return $response;
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
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);
    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
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
  public function createPersistentVolumeClaim($name, $access_mode, $storage) {
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $persistentVolumeClaim = [
      'apiVersion' => 'v1',
      'kind' => 'PersistentVolumeClaim',
      'metadata' => [
        'name' => $name,
      ],
      'spec' => [
        'accessModes' => [
          $access_mode,
        ],
        'resources' => [
          'requests' => [
            'storage' => $storage,
          ],
        ],
      ],
    ];

    $response = $this->request($resourceMethod['action'], $uri, $persistentVolumeClaim);

    if ($response['response'] === 201) {
      return $response;
    }
    else {
      return FALSE;
    }
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
  public function deletePersistentVolumeClaim($name) {
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @inheritdoc
   */
  public function getDeploymentConfig($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if ($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }

  }

  /**
   * @inheritdoc
   */
  public function createDeploymentConfig($name, $image_stream_tag, $image_name, $data) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $deploymentConfig = [
      'apiVersion' => 'v1',
      'kind' => 'DeploymentConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to deploy the application server',
        ],
        'name' => $name,
      ],
      'spec' => [
        'replicas' => 1,
        'selector' => [
          'name' => $name,
        ],
        'strategy' => [
          'resources' => [],
          'rollingParams' => [
              'intervalSeconds' => 1,
              'maxSurge' => '25%',
              'maxUnavailable' => '25%',
              'timeoutSeconds' => 600,
              'updatePeriodSeconds' => 1,
          ],
          'type' => 'Rolling',
        ],
        'template' => [
            'metadata' => [
                'annotations' => [
                    'openshift.io/container.' . $image_name . '.image.entrypoint' => '["/usr/local/s2i/run"]',
                  ],
                'labels' => [
                    'name' => $name,
                  ],
                'name' => $name,
            ],
            'spec' =>
              [
                'containers' =>
                  [
                    [
                      'env' => isset($data['env_vars']) ? $data['env_vars'] : [],
                      'image' => ' ',
                      'name' => $name,
                      'ports' =>
                        [
                          [
                            'containerPort' => isset($data['containerPort']) ? $data['containerPort'] : NULL
                          ],
                        ],
                      'resources' =>
                        [
                          'limits' =>
                            [
                              'memory' => isset($data['memory_limit']) ? $data['memory_limit'] : '',
                            ],
                        ],
                      'volumeMounts' =>
                        [
                          [
                            'mountPath' => '/code/web/sites/default/files',
                            'name' => $data['public_volume'],
                          ],
                          [
                            'mountPath' => '/code/private',
                            'name' => $data['private_volume'],
                          ],
                        ],
                    ],
                  ],
                'dnsPolicy' => 'ClusterFirst',
                'restartPolicy' => 'Always',
                'securityContext' => [],
                'terminationGracePeriodSeconds' => 30,
                'volumes' =>
                  [
                    [
                      'name' => $data['public_volume'],
                      'persistentVolumeClaim' =>
                        [
                          'claimName' => $data['public_volume'],
                        ],
                    ],
                    [
                      'name' => $data['private_volume'],
                      'persistentVolumeClaim' =>
                        [
                          'claimName' => $data['private_volume'],
                        ],
                    ],
                  ],
              ],
          ],
        'test' => FALSE,
        'triggers' => [
            [
              'imageChangeParams' => [
                  'automatic' => TRUE,
                  'containerNames' => [ $name ],
                  'from' => [
                      'kind' => 'ImageStreamTag',
                      'name' => $image_stream_tag . ':latest',
                    ],
                ],
              'type' => 'ImageChange',
            ],
            [
              'type' => 'ConfigChange',
            ],
        ],
      ],
      // According to the docs this is required.
      // @see : https://docs.openshift.org/latest/rest_api/openshift_v1.html#v1-deploymentconfig
      'status' => [
        'lastestVersion' => 0,
        'observedGeneration' => 0,
        'replicas' => 1,
        'updatedReplicas' => 0,
        'availableReplicas' => 0,
        'unavailableReplicas' => 0,
      ]
    ];

    $response = $this->request($resourceMethod['action'], $uri, $deploymentConfig);

    if ($response['response'] === 201) {
      return $response;
    }
    else {
      return FALSE;
    }


  }

  /**
   * @inheritdoc
   */
  public function updateDeploymentConfig($name, $image_stream_tag, $image_name, $data) {
    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name
    ]);

    $deploymentConfig = [
      'apiVersion' => 'v1',
      'kind' => 'DeploymentConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to deploy the application server',
        ],
        'name' => $name,
      ],
      'spec' => [
        'replicas' => 1,
        'selector' => [
          'name' => $name,
        ],
        'strategy' => [
          'resources' => [],
          'rollingParams' => [
            'intervalSeconds' => 1,
            'maxSurge' => '25%',
            'maxUnavailable' => '25%',
            'timeoutSeconds' => 600,
            'updatePeriodSeconds' => 1,
          ],
          'type' => 'Rolling',
        ],
        'template' => [
          'metadata' => [
            'annotations' => [
              'openshift.io/container.' . $image_name . '.image.entrypoint' => '["/usr/local/s2i/run"]',
            ],
            'labels' => [
              'name' => $name,
            ],
            'name' => $name,
          ],
          'spec' =>
            [
              'containers' =>
                [
                  [
                    'env' => isset($data['env_vars']) ? $data['env_vars'] : [],
                    'image' => ' ',
                    'name' => $name,
                    'ports' =>
                      [
                        [
                          'containerPort' => isset($data['containerPort']) ? $data['containerPort'] : NULL
                        ],
                      ],
                    'resources' =>
                      [
                        'limits' =>
                          [
                            'memory' => isset($data['memory_limit']) ? $data['memory_limit'] : '',
                          ],
                      ],
                    'volumeMounts' =>
                      [
                        [
                          'mountPath' => '/code/web/sites/default/files',
                          'name' => $name . '-public',
                        ],
                        [
                          'mountPath' => '/code/private',
                          'name' => $name . '-private',
                        ],
                      ],
                  ],
                ],
              'dnsPolicy' => 'ClusterFirst',
              'restartPolicy' => 'Always',
              'securityContext' => [],
              'terminationGracePeriodSeconds' => 30,
              'volumes' =>
                [
                  [
                    'name' => $name . '-public',
                    'persistentVolumeClaim' =>
                      [
                        'claimName' => $name . '-public',
                      ],
                  ],
                  [
                    'name' => $name . '-private',
                    'persistentVolumeClaim' =>
                      [
                        'claimName' => $name . '-private',
                      ],
                  ],
                ],
            ],
        ],
        'test' => FALSE,
        'triggers' => [
          [
            'imageChangeParams' => [
              'automatic' => TRUE,
              'containerNames' => [ $name ],
              'from' => [
                'kind' => 'ImageStreamTag',
                'name' => $image_stream_tag . ':latest',
              ],
            ],
            'type' => 'ImageChange',
          ],
          [
            'type' => 'ConfigChange',
          ],
        ],
      ]
    ];

    $response = $this->request($resourceMethod['action'], $uri, $deploymentConfig);

    if($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }

  }

  /**
   * @inheritdoc
   */
  public function deleteDeploymentConfig($name) {

    $method = __METHOD__;
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name
    ]);

    $response = $this->request($resourceMethod['action'], $uri);

    if($response['response'] === 200) {
      return $response;
    }
    else {
      return FALSE;
    }

  }
}
