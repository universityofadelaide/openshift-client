<?php

namespace UniversityOfAdelaide\OpenShift;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

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
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * Resource map.
   *
   * @var array
   */
  protected $resourceMap = [
    'backup' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/backups',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/backups/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/backups/{name}',
      ],
      'list' => [
        'action' => 'GET',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/backups',
      ],
    ],
    'buildconfig' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/oapi/v1/namespaces/{namespace}/buildconfigs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/oapi/v1/namespaces/{namespace}/buildconfigs/{name}',
      ],
    ],
    'builds' => [
      'get' => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/builds/{name}',
      ],
    ],
    'cronjob' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/apis/batch/v1beta1/namespaces/{namespace}/cronjobs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/apis/batch/v1beta1/namespaces/{namespace}/cronjobs/{name}',
      ],
      'get' => [
        'action' => 'GET',
        'uri'    => '/apis/batch/v1beta1/namespaces/{namespace}/cronjobs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/apis/batch/v1beta1/namespaces/{namespace}/cronjobs/{name}',
      ],
    ],
    'deploymentconfig' => [
      'create'      => [
        'action' => 'POST',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs',
      ],
      'delete'      => [
        'action' => 'DELETE',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
      'get'         => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
      'update'      => [
        'action' => 'PUT',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}',
      ],
      'instantiate' => [
        'action' => 'POST',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs/{name}/instantiate',
      ],
    ],
    'deploymentconfigs' => [
      'get' => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/deploymentconfigs',
      ],
    ],
    'imagestream' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/oapi/v1/namespaces/{namespace}/imagestreams',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/oapi/v1/namespaces/{namespace}/imagestreams/{name}',
      ],
    ],
    'imagestreamtag' => [
      'get' => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/imagestreamtags/{name}',
      ],
    ],
    'job' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/apis/batch/v1/namespaces/{namespace}/jobs',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/apis/batch/v1/namespaces/{namespace}/jobs/{name}',
      ],
    ],
    'persistentvolumeclaim' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/api/v1/namespaces/{namespace}/persistentvolumeclaims',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/api/v1/namespaces/{namespace}/persistentvolumeclaims/{name}',
      ],
    ],
    'pod' => [
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/pods/{name}',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/api/v1/namespaces/{namespace}/pods/{name}',
      ],
    ],
    'pods' => [
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/pods',
      ],
    ],
    'replicationcontrollers' => [
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/api/v1/namespaces/{namespace}/replicationcontrollers/{name}',
      ],
    ],
    'restore' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/restores',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/restores/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/restores/{name}',
      ],
      'list' => [
        'action' => 'GET',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/restores',
      ],
    ],
    'route' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/oapi/v1/namespaces/{namespace}/routes',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/oapi/v1/namespaces/{namespace}/routes/{name}',
      ],
    ],
    'schedule' => [
      'get' => [
        'action' => 'GET',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/schedules/{name}',
      ],
      'create' => [
        'action' => 'POST',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/schedules',
      ],
      'update' => [
        // We use PATCH here since we're using nicely serialized data.
        'action' => 'PATCH',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/schedules/{name}',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/apis/extension.shepherd/v1/namespaces/{namespace}/schedules/{name}',
      ],
    ],
    'secret' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/api/v1/namespaces/{namespace}/secrets',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/api/v1/namespaces/{namespace}/secrets/{name}',
      ],
    ],
    'service' => [
      'create' => [
        'action' => 'POST',
        'uri'    => '/api/v1/namespaces/{namespace}/services',
      ],
      'delete' => [
        'action' => 'DELETE',
        'uri'    => '/api/v1/namespaces/{namespace}/services/{name}',
      ],
      'get'    => [
        'action' => 'GET',
        'uri'    => '/api/v1/namespaces/{namespace}/services/{name}',
      ],
      'group'  => [
        'action' => 'PATCH',
        'uri'    => '/api/v1/namespaces/{namespace}/services/{name}',
      ],
      'update' => [
        'action' => 'PUT',
        'uri'    => '/api/v1/namespaces/{namespace}/services/{name}',
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
    'PATCH' => 200,
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
        'Accept' => 'application/json',
      ],
    ]);
    $this->serializer = OpenShiftSerializerFactory::create();
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
    $this->apiVersion = $apiVersion;
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
   * Recurse into the array and remove any keys with empty values.
   *
   * @param array $array
   *   The array to process.
   *
   * @return array
   *   The processed array with empty data removed.
   */
  private function filterEmptyArrays(array $array) {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        if (empty($value)) {
          unset($array[$key]);
        }
        else {
          $array[$key] = $this->filterEmptyArrays($value);
        }
      }
    }
    return $array;
  }

  /**
   * {@inheritdoc}
   */
  public function request(string $method, string $uri, $body = NULL, array $query = [], $decode_response = TRUE) {
    $requestOptions = [];

    // Openshift API borks on empty array parameters, remove them.
    if (is_array($body)) {
      $body = $this->filterEmptyArrays($body);
    }

    if ($method !== 'DELETE') {
      $requestOptions = [
        'query' => $query,
        'body' => is_array($body) ? json_encode($body) : $body,
      ];
    }

    if ($method === 'PATCH') {
      $requestOptions['headers']['Content-Type'] = 'application/merge-patch+json';
    }
    else {
      $requestOptions['headers']['Content-Type'] = 'application/json';
    }

    try {
      $response = $this->guzzleClient->request($method, $uri, $requestOptions);
    }
    catch (RequestException $e) {
      // If the exception is a 'not found' response to a GET or DELETE, just return false.
      if (($method === 'GET' || $method === 'DELETE') && $e->getCode() === 404) {
        return FALSE;
      }
      // Do some special decoding for OpenShift.
      if ($e->hasResponse()) {
        $message = json_decode($e->getResponse()->getBody()->getContents());
      }
      throw new ClientException(
        isset($message) ? $message->message : $e->getMessage(),
        $e->getCode(),
        $e->getPrevious(),
        $e->hasResponse() ? $e->getResponse()->getBody() : ''
      );
    }
    $contents = $response->getBody()->getContents();
    return $decode_response ? json_decode($contents, TRUE) : $contents;
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
      'kind' => 'Secret',
      'metadata' => [
        'name' => $name,
        'labels' => [
          'app' => $name,
        ],
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
      'kind' => 'Secret',
      'metadata' => [
        'name' => $name,
        'labels' => [
          'app' => $name,
        ],
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
  public function createService(string $name, string $deployment_name, int $port, int $target_port, string $app_name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    // @todo - use a model.
    $service = [
      'kind' => 'Service',
      'metadata' => [
        'name' => $name,
        'labels' => ['app' => $app_name],
      ],
      'spec' => [
        'ports' => [
          // Defaults to TCP.
          [
            // @todo Does this have any impact when using non web ports?
            'name' => 'web',
            'port' => $port,
            'targetPort' => $target_port,
          ],
        ],
        'selector' => [
          'deploymentconfig' => $deployment_name,
        ],
      ],
    ];

    $annotations = ['app' => $app_name];
    $this->applyAnnotations($service, $annotations);

    $result = $this->request($resourceMethod['action'], $uri, $service);
    if ($result && $app_name !== $name) {
      // @todo - there is a possibility for a race condition if the group triggers
      // before the previous request has been completed.
      $this->groupService($app_name, $name);
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function updateService(string $name, string $selector) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name,
    ]);

    $service = $this->getService($name);

    $service['spec']['selector']['deploymentconfig'] = $selector;

    return $this->request($resourceMethod['action'], $uri, $service);
  }

  /**
   * {@inheritdoc}
   */
  public function groupService(string $app_name, string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $app_name,
    ]);

    $parent_service = $this->getService($app_name);
    if ($parent_service) {
      $annotations['service.alpha.openshift.io/dependencies'] =
        t('[{"name": "@name", "kind": "Service"}]', ['@name' => $name]);

      $this->request($resourceMethod['action'], $uri, ['metadata' => ['annotations' => $annotations]]);
    }
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
  public function createRoute(string $name, string $service_name, string $domain, string $path = NULL, array $annotations = []) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $route = [
      'kind' => 'Route',
      'metadata' => [
        'name' => $name,
        'labels' => ['app' => $name],
      ],
      'spec' => [
        'host' => $domain,
        'path' => $path,
        'to' => [
          'kind' => 'Service',
          'name' => $service_name,
        ],
      ],
    ];

    if (count($annotations)) {
      $route['metadata']['annotations'] = $annotations;
    }

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
  public function getBuilds(string $name, string $label) {
    return $this->apiCall(__METHOD__, '', $label);
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
  public function generateBuildConfig(string $name, string $secret, string $image_stream_tag, array $data) {
    $build_config = [
      'kind' => 'BuildConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to build the application',
        ],
        'name' => $name,
      ],
      'spec' => [
        'resources' => [
          'limits' => [
            'cpu' => $data['cpu_limit'] ?? '0m',
            'memory' => $data['memory_limit'] ?? '1Gi',
          ],
          'requests' => [
            'cpu' => $data['cpu_request'] ?? '0m',
            'memory' => $data['memory_request'] ?? '0Mi',
          ],
        ],
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
            'env' => $data['env_vars'] ?? [],
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
    return $build_config;
  }

  /**
   * {@inheritdoc}
   */
  public function createBuildConfig(array $build_config) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    return $this->request($resourceMethod['action'], $uri, $build_config);
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
  public function generateImageStreamConfig(string $name) {
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
  public function createImageStream(array $image_stream_config) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $image_stream_config);
  }

  /**
   * {@inheritdoc}
   */
  public function updateImageStream(string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);

    $imageStreamConfig = $this->generateImageStreamConfig($name);

    return $this->request($resourceMethod['action'], $this->createRequestUri($resourceMethod['uri']), $imageStreamConfig);
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
  public function createPersistentVolumeClaim(string $name, string $access_mode, string $storage, string $deployment_name, string $storage_class = '') {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $pvc = [
      'apiVersion' => 'v1',
      'kind' => 'PersistentVolumeClaim',
      'metadata' => [
        'name' => $name,
        'labels' => ['app' => $deployment_name],
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

    if (!empty($storage_class)) {
      $pvc['spec']['storageClassName'] = $storage_class;
    }

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
  public function getDeploymentConfigs(string $label = NULL) {
    // If there is no labelSelector retrieve all in the namespace.
    return $this->apiCall(__METHOD__, '', $label);
  }

  /**
   * {@inheritdoc}
   */
  public function generateDeploymentConfig(string $name, string $image_stream_tag, string $image_name, bool $update_on_image_change = FALSE, array $volumes = [], array $data = [], array $probes = []) {
    $volume_config = $this->setVolumes($volumes);

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
          ],
          'spec' =>
            [
              'containers' =>
                [
                  [
                    'env' => $data['env_vars'] ?? [],
                    'image' => ' ',
                    'name' => $name,
                    'ports' =>
                      [
                        [
                          'containerPort' => $data['containerPort'] ?? NULL,
                        ],
                      ],
                    'resources' =>
                      [
                        'limits' =>
                          [
                            'cpu' => $data['cpu_limit'] ?? '0m',
                            'memory' => $data['memory_limit'] ?? '0Mi',
                          ],
                        'requests' =>
                          [
                            'cpu' => $data['cpu_request'] ?? '0m',
                            'memory' => $data['memory_request'] ?? '0Mi',
                          ],
                      ],
                    'volumeMounts' => $volume_config['mounts'],
                  ],
                ],
              'dnsPolicy' => 'ClusterFirst',
              'restartPolicy' => 'Always',
              'terminationGracePeriodSeconds' => 30,
              'volumes' => $volume_config['config'],
            ],
        ],
        'test' => FALSE,
        'triggers' => [
          [
            'imageChangeParams' => [
              'automatic' => $update_on_image_change,
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
            'configChangeParams' => [
              'automatic' => TRUE,
            ],
          ],
        ],
      ],
    ];

    if (array_key_exists('annotations', $data)) {
      $this->applyAnnotations($deploymentConfig, $data['annotations']);
    }

    // v3.11 complains if the securityContext is blank, only create if needed.
    if (array_key_exists('uid', $data)) {
      $deploymentConfig['spec']['template']['spec'] +=
        $this->generateSecurityContext($data);
    }

    if (!empty($probes)) {
      $deploymentConfig['spec']['template']['spec']['containers'][0] +=
        $this->generateProbeConfigs($probes);
    }

    return $deploymentConfig;
  }

  /**
   * Return a formatted securityContext for openshift.
   *
   * TODO: Move uid/gid into a sub array and only pass that?
   *
   * @param $data
   *   The complete data array
   *
   * @return array
   *   Security array ready for API.
   */
  protected function generateSecurityContext($data) {
    return [
      'securityContext' => [
        'runAsUser' => $data['uid'],
        'supplementalGroups' => array_key_exists('gid', $data) ? [$data['gid']] : [],
      ]
    ];
  }

  /**
   * Return an array of probes.
   *
   * @param $probes
   *  Array of probe configuration constructed from a project entity.
   *
   * @return array
   *   Probes array ready for API.
   */
  protected function generateProbeConfigs($probes) {
    $probeConfigs = [];
    foreach (['liveness', 'readiness'] as $type) {
      if (!empty($probes[$type])) {
        $probeConfigs[$type . 'Probe'] =
          $this->generateprobeConfig($probes[$type]);
      }
    }
    return $probeConfigs;
  }

  /**
   * Generates probe configuration based on probe type.
   *
   * @param array $probe
   *   A single probe configuration array.
   *
   * @return array
   *   Config array to be added to the Deployment Config.
   */
  protected function generateprobeConfig(array $probe) {
    $probeConfig = [];
    switch ($probe['type']) {
      case 'exec':
        $probeConfig = [
          'initialDelaySeconds' => 10,
          'timeoutSeconds' => 10,
          'exec' => [
            'command' => explode(' ', $probe['parameters']),
          ],
        ];
        break;

      case 'httpGet':
        $probeConfig = [
          'initialDelaySeconds' => 10,
          'timeoutSeconds' => 10,
          'httpGet' => [
            'port' => (int) $probe['port'],
            'path' => $probe['parameters'],
          ],
        ];
        break;

      case 'tcpSocket':
        $probeConfig = [
          'initialDelaySeconds' => 10,
          'timeoutSeconds' => 10,
          'tcpSocket' => [
            'port' => (int) $probe['port'],
          ],
        ];
        break;
    }
    return $probeConfig;
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
  public function instantiateDeploymentConfig(string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name,
    ]);

    $instantiate = [
      'apiVersion' => 'v1',
      'kind' => 'DeploymentRequest',
      'name' => $name,
      'latest' => TRUE,
      'force' => TRUE,
    ];

    return $this->request($resourceMethod['action'], $uri, $instantiate);
  }

  /**
   * {@inheritdoc}
   */
  public function updateDeploymentConfig(string $name, array $deployment_config, array $config) {
    $deployment_config = array_replace_recursive($deployment_config, $config);
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name,
    ]);

    return $this->request($resourceMethod['action'], $uri, $deployment_config);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteDeploymentConfig(string $name) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => $name,
    ]);

    return $this->request($resourceMethod['action'], $uri);
  }

  /**
   * {@inheritdoc}
   */
  public function getCronJob(string $name, string $label = '') {
    return $this->apiCall(__METHOD__, $name, $label);
  }

  /**
   * {@inheritdoc}
   */
  public function createCronJob(string $name, string $image_name, string $schedule, bool $cron_suspended, array $args, array $volumes, array $data) {
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);

    $volume_config = $this->setVolumes($volumes);
    $job_template = $this->jobTemplate($name, $image_name, $args, $volume_config, $data);

    $cronConfig = [
      'apiVersion' => 'batch/v1beta1',
      'kind' => 'CronJob',
      'metadata' => [
        'name' => $name,
        'labels' => array_key_exists('labels', $data) ? array_merge($data['labels'], ['name' => $name]) : [],
      ],
      'spec' => [
        'concurrencyPolicy' => 'Forbid',
        'schedule' => $schedule,
        'suspend' => $cron_suspended,
        'failedJobsHistoryLimit' => 1,
        'successfulJobsHistoryLimit' => 1,
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
  public function updateCronJob(string $name, string $schedule, bool $cron_suspended) {
    // @todo implement more things that can be updated.
    $resourceMethod = $this->getResourceMethod(__METHOD__);
    $uri = $this->createRequestUri($resourceMethod['uri']);
    $cronConfig = $this->getCronJob($name);

    $cronConfig['spec']['schedule'] = $schedule;
    $cronConfig['spec']['suspend'] = $cron_suspended;
    return $this->request($resourceMethod['action'], $uri, $cronConfig);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteCronJob(string $name, string $label = '') {
    // If the name was passed in, just delete that specific one.
    if (!empty($name)) {
      return $this->apiCall(__METHOD__, $name, $label);
    }

    // If there was no name, but is a label, delete all jobs that match.
    if ($cron_jobs = $this->getCronJob($name, $label)) {
      foreach ($cron_jobs['items'] as $job) {
        if (!$result = $this->apiCall(__METHOD__, $job['metadata']['name'])) {
          return $result;
        }
      }
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getJob(string $name, string $label = '') {
    return $this->apiCall(__METHOD__, $name, $label);
  }

  /**
   * {@inheritdoc}
   */
  public function getPods() {
    return $this->apiCall(__METHOD__);
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
        'failedJobsHistoryLimit' => 1,
        'successfulJobsHistoryLimit' => 1,
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
  public function deleteJob(string $name, string $label = '') {
    if (!empty($name)) {
      return $this->apiCall(__METHOD__, $name);
    }

    // If there was no name, but is a label, delete all jobs that match.
    if ($jobs = $this->getJob($name, $label)) {
      foreach ($jobs['items'] as $job) {
        if (!$result = $this->apiCall(__METHOD__, $job['metadata']['name'])) {
          return $result;
        }
      }
    }

    return TRUE;
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
      // @todo - err what ?
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
   * {@inheritdoc}
   */
  public function getBackup(string $name) {
    $result = $this->apiCall(__METHOD__, $name, NULL, FALSE);
    if (!$result) {
      return FALSE;
    }
    return $this->serializer->deserialize($result, Backup::class, 'json');
  }

  /**
   * {@inheritdoc}
   */
  public function listBackup(Label $label_selector = NULL) {
    $label = NULL;
    if ($label_selector) {
      $label = (string) $label_selector;
    }

    $result = $this->apiCall(__METHOD__, '', $label, FALSE);
    if (!$result) {
      return FALSE;
    }
    return $this->serializer->deserialize($result, BackupList::class, 'json');
  }

  /**
   * {@inheritdoc}
   */
  public function createBackup(Backup $backup) {
    return $this->createSerializableObject(__METHOD__, $backup);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteBackup(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function createRestore(Restore $restore) {
    return $this->createSerializableObject(__METHOD__, $restore);
  }

  /**
   * {@inheritdoc}
   */
  public function listRestore(Label $label_selector = NULL) {
    $label = NULL;
    if ($label_selector) {
      $label = (string) $label_selector;
    }

    $result = $this->apiCall(__METHOD__, '', $label, FALSE);
    if (!$result) {
      return FALSE;
    }
    return $this->serializer->deserialize($result, RestoreList::class, 'json');
  }

  /**
   * {@inheritdoc}
   */
  public function getSchedule(string $name) {
    $result = $this->apiCall(__METHOD__, $name, NULL, FALSE);
    if (!$result) {
      return FALSE;
    }
    return $this->serializer->deserialize($result, ScheduledBackup::class, 'json');
  }

  /**
   * {@inheritdoc}
   */
  public function createSchedule(ScheduledBackup $schedule) {
    return $this->createSerializableObject(__METHOD__, $schedule);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSchedule(ScheduledBackup $schedule) {
    return $this->createSerializableObject(__METHOD__, $schedule, ['name' => $schedule->getName()]);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSchedule(string $name) {
    return $this->apiCall(__METHOD__, $name);
  }

  /**
   * Create an object in openshift that supports serialization.
   *
   * @param string $method
   *   The method this has been called from.
   * @param object $object
   *   The object to create.
   * @param array $params
   *   Optional params to pass to createRequestUri.
   *
   * @throws \UniversityOfAdelaide\OpenShift\ClientException
   *   A client exception if the creation failed.
   *
   * @return mixed|bool
   *   Either the object that was created, or false if it failed.
   */
  private function createSerializableObject($method, $object, array $params = []) {
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], $params);
    $serialized = $this->serializer->serialize($object, 'json');
    if (!$result = $this->request($resourceMethod['action'], $uri, $serialized, [], FALSE)) {
      return FALSE;
    }
    return $this->serializer->deserialize($result, get_class($object), 'json');
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
   * Given an array of volumes, structure it into an array for OpenShift.
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
   * Generates a job template.
   *
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
   *   A job template array.
   */
  private function jobTemplate(string $name, string $image_name, array $args, array $volume_config, array $data) {
    $job_template = [
      'spec' => [
        'template' => [
          'metadata' => [
            'name' => $name,
            'labels' => array_key_exists('labels', $data) ? array_merge($data['labels'], ['name' => $name]) : [],
          ],
          'spec' =>
            [
              'containers' =>
                [
                  [
                    'args' => $args,
                    'env' => $data['env_vars'] ?? [],
                    'image' => $image_name,
                    'imagePullPolicy' => 'Always',
                    'name' => $name,
                    'resources' =>
                      [
                        'limits' =>
                          [
                            'cpu' => $data['cpu_limit'] ?? '0m',
                            'memory' => $data['memory_limit'] ?? '0Mi',
                          ],
                        'requests' =>
                          [
                            'cpu' => $data['cpu_request'] ?? '0m',
                            'memory' => $data['memory_request'] ?? '0Mi',
                          ],
                      ],
                    'volumeMounts' => $volume_config['mounts'],
                  ],
                ],
              'dnsPolicy' => 'ClusterFirst',
              'restartPolicy' => 'Never',
              'terminationGracePeriodSeconds' => 30,
              'volumes' => $volume_config['config'],
            ],
        ],
      ],
    ];

    // v3.11 complains if the securityContext is blank, only create if needed.
    if (array_key_exists('uid', $data)) {
      $job_template['spec']['template']['spec'] +=
        $this->generateSecurityContext($data);
    }

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
   * @param bool $decode_response
   *   Whether to decode the response or not.
   *
   * @return array|bool
   *   Return the item, or false if the retrieve failed.
   *
   * @throws \UniversityOfAdelaide\OpenShift\ClientException
   */
  private function apiCall(string $method, $name = '', $label = NULL, $decode_response = TRUE) {
    $resourceMethod = $this->getResourceMethod($method);
    $uri = $this->createRequestUri($resourceMethod['uri'], [
      'name' => (string) $name,
    ]);

    $query = [];
    if (!empty($label)) {
      $query = ['labelSelector' => $label];
    }

    return $this->request($resourceMethod['action'], $uri, [], $query, $decode_response);
  }

}
