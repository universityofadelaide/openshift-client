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
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}'
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
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}'
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
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}'
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
    'imagestreamtag' => [
      'get' => [
        'action' => 'GET',
        'uri' => 'oapi/v1/namespaces/{namespace}/imagestreamtags/{name}'
      ],
    ]
  ];

  /**
   * Expected response codes map.
   *
   * @var array
   */
  protected $responseCodes = [
    'POST' => 201,
    'DELETE' => 200,
    'GET' => 200,
    'PUT' => 200,
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
   * @return array|bool Returns json_decoded body contents or FALSE.
   */
  protected function request($method, $uri, array $body = [], array $query = []) {
    $requestOptions = [];

    if ($method != 'DELETE') {
      $requestOptions = [
        'query' => $query,
        'body' => json_encode($body),
      ];
    }

    try {
      $response = $this->guzzleClient->request($method, $uri, $requestOptions);
    } catch (RequestException $exception) {
      // @todo Handle errors.
      return FALSE;
    }

    return json_decode($response->getBody()->getContents(), true);
  }

  /**
   * Gets the uri and action from the resourceMap using the class method name.
   *
   * @param string $methodName
   *   The class method name, typically from __METHOD__ magic constant.
   * @return array
   */
  protected function getResourceMethod($methodName) {
    // Strip class name if present.
    $methodName = end(explode('::', $methodName));
    // Split into array by snakeCaseWordBoundaries.
    $nameParts = preg_split('/(?=[A-Z])/', $methodName);
    // The first element is the action (e.g. 'create').
    $action = array_shift($nameParts);
    // The remaining elements are the resource (e.g. 'deploymentconfig').
    $resource = strtolower(implode('', $nameParts));

    return $this->resourceMap[$resource][$action];
  }

  /**
   * Creates a relative request url.
   *
   * @param string $uri The URI to be parsed.
   * @param array $params Params that map to the uri resource path e.g.
   * /api/{namespace}/{name}
   *
   * @return string The request uri.
   */
  protected function createRequestUri(string $uri, array $params = []) {
    // By default replace the {namespace} this is set in configuration.
    $params['namespace'] = $this->namespace;

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
    $resourceMethod = $this->getResourceMethod(__METHOD__);

    // Base64 encode the data.
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

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $secret);
  }

  /**
   * @inheritdoc
   */
  public function getSecret($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri, []);
  }

  /**
   * @inheritdoc
   */
  public function updateSecret($name, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    // Base64 encode the data.
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

    return $this->request($resourceMethod['action'], $uri, $secret);
  }

  /**
   * @inheritdoc
   */
  public function deleteSecret($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    // @todo - use a model.
    $service = [
      'kind'     => 'Service',
      'metadata' => [
        'name'        => (string) $name,
      ],
      'spec' => [
        'ports' => [
          // Defaults to TCP.
          [
            'name'       => 'web',
            'port'       => (int) $data['port'],
            'targetPort' => (int) $data['targetPort'],
          ],
        ],
        'selector' => [
          'name' => $data['deployment'],
        ],
      ],
    ];

    return $this->request($resourceMethod['action'], $uri, $service);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
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

    return $this->request($resourceMethod['action'], $uri, $route);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function getBuildConfig($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function createBuildConfig($name, $secret, $image_stream_tag, $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
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
            'name' => $image_stream_tag,
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

    return $this->request($resourceMethod['action'], $uri, $buildConfig);
  }

  /**
   * @inheritdoc
   */
  public function updateBuildConfig($name, $secret, $imagestream, $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

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

    return $this->request($resourceMethod['action'], $uri, $buildConfig);
  }

  /**
   * @inheritdoc
   */
  public function deleteBuildConfig($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function getImageStream($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function createImageStream($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

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

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);
  }

  /**
   * @inheritdoc
   */
  public function updateImageStream($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

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

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);
  }

  /**
   * @inheritdoc
   */
  public function deleteImageStream($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name
    ]);
    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function getImageStreamTag($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
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

    return $this->request($resourceMethod['action'], $uri, $persistentVolumeClaim);
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
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function getDeploymentConfig($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'],[
      'name' => $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * @inheritdoc
   */
  public function createDeploymentConfig($name, $image_stream_tag, $image_name, $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
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
                          [
                            'mountPath' => '/etc/secret-volume',
                            'name' => 'secret-volume',
                            'readOnly' => TRUE,
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
                    [
                      'name' => 'secret-volume',
                      'secret' => [
                        'secretName' => $data['secret-volume'],
                      ]
                    ]
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
                      'name' => $image_stream_tag,
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

    return $this->request($resourceMethod['action'], $uri, $deploymentConfig);
  }

  /**
   * @inheritdoc
   */
  public function updateDeploymentConfig($name, $image_stream_tag, $image_name, $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
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

    return $this->request($resourceMethod['action'], $uri, $deploymentConfig);
  }

  /**
   * @inheritdoc
   */
  public function deleteDeploymentConfig($name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }
}
