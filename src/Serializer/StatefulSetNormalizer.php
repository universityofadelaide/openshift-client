<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\StatefulSet;

/**
 * Serializer for StatefulSet objects.
 */
class StatefulSetNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = StatefulSet::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\StatefulSet $configMap */
    $configMap = StatefulSet::create();
    $configMap->setName($data['metadata']['name'])
      ->setLabels($data['metadata']['labels'] ?? [])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setSpec($data['spec']);

    return $configMap;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    // If securityContext is empty, it must be an object.
    // Replace with preserve_empty_objects in the client when
    // symfony/serializer is updated.
    // @see https://github.com/symfony/symfony/pull/28363/files#diff-cf0df583a97c223ac656cd9228cc4966R206
    $spec = $object->getSpec();
    if (empty($spec['template']['spec']['securityContext'])) {
      $spec['template']['spec']['securityContext'] = new \stdClass();
    }

    /** @var \UniversityOfAdelaide\OpenShift\Objects\StatefulSet $object */
    $data = [
      'apiVersion' => 'apps/v1',
      'kind' => 'StatefulSet',
      'metadata' => [
        'name' => $object->getName(),
        'labels' => $object->getLabels(),
      ],
      'spec' => $spec,
    ];
    return $data;
  }

}
