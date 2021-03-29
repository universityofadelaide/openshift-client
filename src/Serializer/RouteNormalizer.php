<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Route;

/**
 * Serializer for Route objects.
 */
class RouteNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = Route::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Route $route */
    $route = Route::create();
    $route->setName($data['metadata']['name']);
    return $route;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Route $object */
    $data = [
      'apiVersion' => 'v1',
      'kind' => 'Route',
      'metadata' => [
        'name' => $object->getName(),
      ],
      'spec' => [
        'host' => $object->getHost(),
        'path' => $object->getPath(),
        'tls' => [
          'insecureEdgeTerminationPolicy' => $object->getInsecureEdgeTerminationPolicy(),
          'termination' => $object->getTermination(),
        ],
        'to' => [
          'kind' => $object->getToKind(),
          'name' => $object->getToName(),
          'weight' => $object->getToWeight(),
        ],
        'wildcardPolicy' => $object->getWildcardPolicy(),
      ],
    ];
    if ($object->getLabels()) {
      $data['metadata']['labels'] = $object->getLabels();
    }
    if ($object->getAnnotations()) {
      $data['metadata']['annotations'] = $object->getAnnotations();
    }
    return $data;
  }

}
