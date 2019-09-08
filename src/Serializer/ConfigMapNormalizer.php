<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\ConfigMap;

/**
 * Serializer for ConfigMap objects.
 */
class ConfigMapNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = ConfigMap::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\ConfigMap $configMap */
    $configMap = ConfigMap::create();
    $configMap->setName($data['metadata']['name'])
      ->setLabels($data['metadata']['labels'] ?? [])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setData($data['data'] ?? []);

    return $configMap;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\ConfigMap $object */
    $data = [
      'apiVersion' => 'v1',
      'kind' => 'ConfigMap',
      'metadata' => [
        'name' => $object->getName(),
      ],
      'data' => $object->getData(),
    ];
    if (!empty($object->getLabels())) {
      $data['metadata']['labels'] = $object->getLabels();
    }
    return $data;
  }

}
