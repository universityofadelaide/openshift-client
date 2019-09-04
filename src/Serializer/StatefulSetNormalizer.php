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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\StatefulSet $object */
    $data = [
      'apiVersion' => 'apps/v1',
      'kind' => 'StatefulSet',
      'metadata' => [
        'name' => $object->getName(),
        'labels' => $object->getLabels(),
      ],
      'spec' => $object->getSpec(),
    ];
    return $data;
  }

}
