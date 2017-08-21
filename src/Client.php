<?php

namespace UniversityOfAdelaide\OpenShift;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Client.
 *
 * Provides a client using guzzle to interact easily
 * with the OpenShift api.
 *
 * @package UniversityOfAdelaide\OpenShift
 */
class Client implements ClientInterface {

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
   * Guzzle HTTP Client.
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
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
      'update' => [
        // PUT replaces the entire secret.
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
    ],
    'imagestream' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
      'update' => [
        // PUT replaces the imagestream.
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
    ],
    'buildconfig' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
    ],
    'deploymentconfig' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
    ],
    'deploymentconfigs' => [
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/deploymentconfigs',
      ],
    ],
    'service' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/api/v1/namespaces/{namespace}/services',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/services/{name}',
      ],
      'get' => [
        // Lists all services.
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/services',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/services/{name}',
      ],
    ],
    'route' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
    ],
    'persistentvolumeclaim' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
    ],
    'imagestreamtag' => [
      'get' => [
        'action' => 'GET',
        'uri' => '/oapi/v1/namespaces/{namespace}/imagestreamtags/{name}',
      ],
    ],
    'pod' => [
      'get' => [
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/pods/{name}',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/pods/{name}',
      ],
    ],
    'replicationcontrollers' => [
      'get' => [
        'action' => 'GET',
        'uri' => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
    ],
    'cronjob' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/apis/batch/v2alpha1/namespaces/{namespace}/cronjobs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/apis/batch/v2alpha1/namespaces/{namespace}/cronjobs/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/apis/batch/v2alpha1/namespaces/{namespace}/cronjobs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/apis/batch/v2alpha1/namespaces/{namespace}/cronjobs/{name}',
      ],
    ],
    'job' => [
      'create' => [
        'action' => 'POST',
        'uri' => '/apis/batch/v1/namespaces/{namespace}/jobs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri' => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri' => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri' => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
    ],

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
   * {@inheritdoc}
   */
  public function __construct($host, $token, $namespace, $verifyTls = TRUE) {
    $this->host = $host;
    $this->namespace = $namespace;
    $this->guzzleClient = new GuzzleClient([
      'verify' => $verifyTls,
      'base_uri' => $host,
      'headers' => [
        'Authorization' => 'Bearer ' . $token,
        // @todo - make this configurable.
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
      ],
    ]);
  }

  /**
   * Returns the api version.
   *
   * @return string
   *   The currently supported api version.
   */
  public function getApiVersion() {
    return $this->apiVersion;
  }

  /**
   * Set the api version number.
   *
   * @param string $apiVersion
   *   Api version number.
   */
  public function setApiVersion(string $apiVersion) {
    $this->apiVersion = (string) $apiVersion;
  }

  /**
   * Returns the guzzle client.
   *
   * @return \GuzzleHttp\Client
   *   Return the guzzle client.
   */
  public function getGuzzleClient() {
    return $this->guzzleClient;
  }

  /**
   * {@inheritdoc}
   */
  public function request(string $method, string $uri, array $body = [], array $query = []) {
    $requestOptions = [];

    if ($method != 'DELETE') {
      $requestOptions = [
        'query' => $query,
        'body' => json_encode($body),
      ];
    }

    try {
      $response = $this->guzzleClient->request($method, $uri, $requestOptions);
    }
    catch (RequestException $e) {
      // If the exception is a 'not found' response to a GET, just return false.
      if ($method === 'GET' && $e->getCode() === 404) {
        return FALSE;
      }
      // Do some special decoding for OpenShift
      if ($e->hasResponse()) {
        $message = json_decode($e->getResponse()->getBody()->getContents());
      }
      throw new ClientException(
        isset($message) ? $message->message : '',
        $e->getCode(),
        $e->getPrevious(),
        $e->hasResponse() ? $e->getResponse()->getBody() : ''
      );
    }
    return json_decode($response->getBody()->getContents(), TRUE);
  }

  /**
   * Gets the uri and action from the resourceMap using the class method name.
   *
   * @param string $methodName
   *   The class method name, typically from __METHOD__ magic constant.
   *
   * @return array
   *   The information on how to call the method.
   */
  protected function getResourceMethod(string $methodName) {
    // Strip class name if present.
    $exploded_class = explode('::', $methodName);
    $methodName = end($exploded_class);
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
   * @param string $uri
   *   The URI to be parsed.
   * @param array $params
   *   Params that map to the uri resource path e.g.
   *   /api/{namespace}/{name}.
   *
   * @return string
   *   The request uri.
   */
  protected function createRequestUri(string $uri, array $params = []) {
    // By default replace the {namespace} this is set in configuration.
    $params['namespace'] = $this->namespace;

    foreach ($params as $key => $param) {
      // Perform a string replace on the uri.
      $uri = str_replace('{' . $key . '}', $param, $uri);
    }

    return $uri;
  }

  /**
   * {@inheritdoc}
   */
  public function createSecret(string $name, array $data) {
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
        'name' => $name,
      ],
      'type' => 'Opaque',
      'data' => $data,
    ];

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $secret);
  }

  /**
   * {@inheritdoc}
   */
  public function getSecret(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSecret(string $name, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name,
    ]);

    // Base64 encode the data.
    foreach ($data as $key => $value) {
      $data[$key] = base64_encode($value);
    }

    $secret = [
      'api_version' => 'v1',
      'kind' => 'Secret',
      'metadata' => [
        'name' => $name,
      ],
      'type' => 'Opaque',
      'data' => $data,
    ];

    return $this->request($resourceMethod['action'], $uri, $secret);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSecret(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getService(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createService(string $name, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    // @todo - use a model.
    $service = [
      'kind' => 'Service',
      'metadata' => [
        'name' => (string) $name,
      ],
      'spec' => [
        'ports' => [
          // Defaults to TCP.
          [
            'name' => 'web',
            'port' => (int) $data['port'],
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
   * {@inheritdoc}
   */
  public function updateService(string $name, array $data) {
    // TODO: Implement updateService() method.
  }

  /**
   * {@inheritdoc}
   */
  public function deleteService(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getRoute(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createRoute(string $name, string $service_name, string $domain, string $path = NULL) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $route = [
      'kind' => 'Route',
      'metadata' => [
        'name' => (string) $name,
      ],
      'spec' => [
        'host' => (string) $domain,
        'path' => (string) $path,
        'to' => [
          'kind' => 'Service',
          'name' => (string) $service_name,
        ],
      ],
      // Unsure if required. @see : https://docs.openshift.org/latest/rest_api/openshift_v1.html#v1-routestatus
      'status' => [
        'ingress' => [],
      ],
    ];

    return $this->request($resourceMethod['action'], $uri, $route);
  }

  /**
   * {@inheritdoc}
   */
  public function updateRoute(string $name, string $service_name, string $application_domain) {
    // TODO: Implement updateRoute() method.
  }

  /**
   * {@inheritdoc}
   */
  public function deleteRoute(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildConfig(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createBuildConfig(string $name, string $secret, string $image_stream_tag, array $data) {
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
          ],
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
                'name' => $secret,
              ],
            ],
          ],
          'sourceSecret' => [
            'name' => $secret,
          ],
        ],
        'strategy' => [
          'sourceStrategy' => [
            'forcePull' => TRUE,
            'incremental' => TRUE,
            'from' => [
              'kind' => (string) $data['source']['type'],
              'name' => (string) $data['source']['name'],
            ],
            'pullSecret' => [
              'name' => $secret,
            ],
          ],
          'type' => 'Source',
        ],
        // @todo - figure out github and other types of triggers
        'triggers' => [
          [
            'type' => 'ImageChange',
          ],
          [
            'type' => 'ConfigChange',
          ],
        ],
        'status' => [
          'lastversion' => time(),
        ],
      ],
    ];

    return $this->request($resourceMethod['action'], $uri, $buildConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function updateBuildConfig(string $name, string $secret, string $image_stream, array $data) {
    // @todo Implement updateBuildConfig() method.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function deleteBuildConfig(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getImageStream(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function generateImageStream(string $name) {
    $imageStream = [
      'apiVersion' => 'v1',
      'kind' => 'ImageStream',
      'metadata' => [
        'name' => $name,
        'annotations' => [
          'description' => 'Keeps track of changes in the application image',
        ],
      ],
      'spec' => [
        'dockerImageRepository' => '',
      ],
    ];

    return $imageStream;
  }

  /**
   * {@inheritdoc}
   */
  public function createImageStream(array $imageStreamConfig) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStreamConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function updateImageStream(string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

    $imageStream = [
      'apiVersion' => 'v1',
      'kind' => 'ImageStream',
      'metadata' => [
        'name' => $name,
        'annotations' => [
          'description' => 'Keeps track of changes in the application image',
        ],
      ],
      'spec' => [
        'dockerImageRepository' => '',
      ],
    ];

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStream);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteImageStream(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getImageStreamTag(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createImageSteamTag(string $name) {
    // TODO: Implement createImageSteamTag() method.
  }

  /**
   * {@inheritdoc}
   */
  public function updateImageSteamTag(string $name) {
    // TODO: Implement updateImageSteamTag() method.
  }

  /**
   * {@inheritdoc}
   */
  public function deleteImageSteamTag(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getPersistentVolumeClaim(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createPersistentVolumeClaim(string $name, string $access_mode, string $storage) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $pvc = [
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

    return $this->request($resourceMethod['action'], $uri, $pvc);
  }

  /**
   * {@inheritdoc}
   */
  public function updatePersistentVolumeClaim(string $name, string $access_mode, string $storage) {
    // TODO: Implement updatePersistentVolumeClaim() method.
  }

  /**
   * {@inheritdoc}
   */
  public function deletePersistentVolumeClaim(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getDeploymentConfig(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getDeploymentConfigs(string $label) {
    return $this->apiCall(__METHOD__, '', $label);
  }

  /**
   * {@inheritdoc}
   */
  public function generateDeploymentConfig(string $name, string $image_stream_tag, string $image_name, array $volumes, array $data) {
    $volume_config = $this->setVolumes($volumes);

    $securityContext = [];
    if (array_key_exists('uid', $data)) {
      $securityContext = [
        'runAsUser' => $data['uid'],
        'supplementalGroups' => array_key_exists('gid', $data) ? [$data['gid']] : [],
      ];
    }

    $deploymentConfig = [
      'apiVersion' => 'v1',
      'kind' => 'DeploymentConfig',
      'metadata' => [
        'name' => $name,
        'labels' => array_key_exists('labels', $data) ? $data['labels'] : [],
      ],
      'spec' => [
        'replicas' => 1,
        'selector' => array_key_exists('labels', $data) ? array_merge($data['labels'], ['name' => $name]) : [],
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
            'labels' => array_key_exists('labels', $data) ? array_merge($data['labels'], ['name' => $name]) : [],
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
                          'containerPort' => isset($data['containerPort']) ? $data['containerPort'] : NULL,
                        ],
                      ],
                    'resources' =>
                      [
                        'limits' =>
                          [
                            'memory' => isset($data['memory_limit']) ? $data['memory_limit'] : '',
                          ],
                      ],
                    'volumeMounts' => $volume_config['mounts'],
                  ],
                ],
              'dnsPolicy' => 'ClusterFirst',
              'restartPolicy' => 'Always',
              'securityContext' => $securityContext,
              'terminationGracePeriodSeconds' => 30,
              'volumes' => $volume_config['config'],
            ],
        ],
        'test' => FALSE,
        'triggers' => [
          [
            'imageChangeParams' => [
              'automatic' => TRUE,
              'containerNames' => [$name],
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
    ];

    if (array_key_exists('annotations', $data)) {
      $this->applyAnnotations($deploymentConfig, $data['annotations']);
    }

    return $deploymentConfig;
  }

  /**
   * {@inheritdoc}
   */
  public function createDeploymentConfig(array $deploymentConfig) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    return $this->request($resourceMethod['action'], $uri, $deploymentConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function updateDeploymentConfig(string $name, int $replica_count) {
    $deploymentConfig = $this->getDeploymentConfig($name);
    if ($replica_count === NULL) {
      return $deploymentConfig;
    }

    $deploymentConfig['spec']['replicas'] = $replica_count;
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name,
    ]);

    return $this->request($resourceMethod['action'], $uri, $deploymentConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteDeploymentConfig(string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name,
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * {@inheritdoc}
   */
  public function getCronJob(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createCronJob(string $name, string $image_name, string $schedule, array $args, array $volumes, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $volume_config = $this->setVolumes($volumes);
    $job_template = $this->jobTemplate($name, $image_name, $args, $volume_config, $data ) ;

    $cronConfig = [
      'apiVersion' => 'batch/v2alpha1',
      'kind' => 'CronJob',
      'metadata' => [
        'name' => $name,
      ],
      'spec' => [
        'concurrencyPolicy' => 'Forbid',
        'schedule' => $schedule,
        'suspend' => FALSE,
        'failedJobsHistoryLimit' => 5,
        'successfulJobsHistoryLimit' => 5,
        'jobTemplate' => $job_template,
      ],
    ];

    if (array_key_exists('annotations', $data)) {
      $this->applyAnnotations($cronConfig, $data['annotations']);
    }

    return $this->request($resourceMethod['action'], $uri, $cronConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function updateCronJob(string $name, string $image_name, string $schedule, array $args, array $volumes, array $data) {

  }

  /**
   * {@inheritdoc}
   */
  public function deleteCronJob(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getJob(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createJob(string $name, string $image_name, array $args, array $volumes, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $volume_config = $this->setVolumes($volumes);
    $job_template = $this->jobTemplate($name, $image_name, $args, $volume_config, $data);

    $jobConfig = [
      'apiVersion' => 'batch/v1',
      'kind' => 'Job',
      'metadata' => [
        'name' => $name,
      ],
      'spec' => [
        'concurrencyPolicy' => 'Forbid',
        'suspend' => FALSE,
        'failedJobsHistoryLimit' => 5,
        'successfulJobsHistoryLimit' => 5,
        'template' => $job_template['spec']['template'],
      ],
    ];

    if (array_key_exists('annotations', $data)) {
      $this->applyAnnotations($jobConfig, $data['annotations']);
    }

    return $this->request($resourceMethod['action'], $uri, $jobConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function updateJob(string $name, string $image_name, array $args, array $volumes, array $data) {

  }

  /**
   * {@inheritdoc}
   */
  public function deleteJob(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }



  /**
   * {@inheritdoc}
   */
  public function getPod($name, $label = NULL) {
    return $this->apiCall(__METHOD__, $name, $label);
  }

  /**
   * {@inheritdoc}
   */
  public function deletePod(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getReplicationControllers($name, $label = NULL) {
    return $this->apiCall(__METHOD__, $name, $label);
  }

  /**
   * {@inheritdoc}
   */
  public function updateReplicationControllers($name, $label = NULL, $replica_count = NULL) {
    $result = FALSE;

    $repControllers = $this->getReplicationControllers($name, $label);
    if ($replica_count === NULL) {
      return $repControllers;
    }

    // If queried with the label, there will be an array of controllers.
    if (isset($repControllers['items'])) {
      foreach ($repControllers['items'] as $controller) {
        $result = $this->updateReplicationControllers($controller['metadata']['name'], NULL, $replica_count);
        if (!$result) {
          return FALSE;
        }
        // @todo this feels wrong, build an array of results?
      }
    }
    else {
      $resourceMethod = $this->getResourceMethod(__METHOD__);
      $uri = $this->createRequestUri($resourceMethod['uri'], [
        'name' => (string) $name,
      ]);

      $repControllers['spec']['replicas'] = $replica_count;

      $result = $this->request($resourceMethod['action'], $uri, $repControllers);
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteReplicationControllers($name, $label = NULL) {
    $result = FALSE;

    $repControllers = $this->getReplicationControllers($name, $label);

    // If queried with the label, there will be an array of controllers.
    if (isset($repControllers['items'])) {
      foreach ($repControllers['items'] as $controller) {
        $result = $this->deleteReplicationControllers($controller['metadata']['name'], NULL);
        if (!$result) {
          return FALSE;
        }
        // @todo this feels wrong, build an array of results?
      }
    }
    else {
      $result = $this->apiCall(__METHOD__, $name, $label);
    }

    return $result;
  }

  /**
   * Merge annotations into the config, if there are any.
   *
   * Applying a blank annotation causes failures, is why this function exists.
   *
   * @param array $config
   *   The config array to have the annotations applied to.
   * @param array $annotations
   *   The annotations to apply.
   */
  private function applyAnnotations(array &$config, array $annotations) {
    if (is_array($annotations)) {
      $config['metadata']['annotations'] = $annotations;
    }
  }

  /**
   * Given an array of volumes, structure it into an array for openshift.
   *
   * @param array $volumes
   *   The array of volumes to format.
   *
   * @return array
   *   The formatted volume structure.
   */
  private function setVolumes(array $volumes) {
    // Construct volume configuration.
    $volumes_config = [];
    $volume_mounts = [];

    foreach ($volumes as $volume) {
      if ($volume['type'] === 'pvc') {
        $volumes_config[] = [
          'name' => $volume['name'],
          'persistentVolumeClaim' => [
            'claimName' => $volume['name'],
          ],
        ];
        $volume_mounts[] = [
          'mountPath' => $volume['path'],
          'name' => $volume['name'],
        ];
      }
      elseif ($volume['type'] === 'secret') {
        $volumes_config[] = [
          'name' => $volume['name'],
          'secret' => [
            'secretName' => $volume['secret'],
          ],
        ];
        $volume_mounts[] = [
          'mountPath' => $volume['path'],
          'name' => $volume['name'],
          'readOnly' => TRUE,
        ];
      }
    }
    return [
      'mounts' => $volume_mounts,
      'config' => $volumes_config,
    ];
  }

  /**
   * @param string $name
   *   Name of job.
   * @param string $image_name
   *   Image name for deployment.
   * @param array $args
   *   The commands to run, each entry in the array is a command.
   * @param array $volume_config
   *   Volumes to attach to the deployment config.
   * @param array $data
   *   Configuration data for deployment config.
   *
   * @return array
   */
  private function jobTemplate($name, $image_name, $args, $volume_config, $data) {
    $job_template = [
      'spec' => [
        'template' => [
          'spec' =>
            [
              'containers' =>
                [
                  [
                    'args' => $args,
                    'env' => isset($data['env_vars']) ? $data['env_vars'] : [],
                    'image' => $image_name,
                    'imagePullPolicy' => 'Never',
                    'name' => $name,
                    'resources' =>
                      [
                        'limits' =>
                          [
                            'memory' => isset($data['memory_limit']) ? $data['memory_limit'] : '',
                          ],
                      ],
                    'volumeMounts' => $volume_config['mounts'],
                  ],
                ],
              'dnsPolicy' => 'ClusterFirst',
              'restartPolicy' => 'Never',
              'securityContext' => [],
              'terminationGracePeriodSeconds' => 30,
              'volumes' => $volume_config['config'],
            ],
        ],
      ],
    ];

    return $job_template;
  }

  /**
   * Common function for very simple request to the api.
   *
   * @param string $method
   *   Method calling get, to lookup the uri.
   * @param string $name
   *   Name of the item to retrieve.
   * @param string $label
   *   Label of items to retrieve.
   *
   * @return array|bool
   *   Return the item, or false if the retrieve failed.
   */
  private function apiCall(string $method, $name = '', $label = NULL) {
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name,
    ]);

    $query = [];
    if (!empty($label)) {
      $query = ['labelSelector' => $label];
    }

    return $this->request($resourceMethod['action'], $uri, [], $query);
  }

}
