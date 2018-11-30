<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment;

/**
 * Serializer for Sync objects.
 */
class SyncNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = Sync::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $sync = Sync::createFromSourceAndTarget(
      SyncEnvironment::createFromPvcAndSecret($data['spec']['source']['persistentVolumeClaim'], $data['spec']['source']['secret']),
      SyncEnvironment::createFromPvcAndSecret($data['spec']['target']['persistentVolumeClaim'], $data['spec']['target']['secret'])
    )->setName($data['metadata']['name']);
    if (isset($data['metadata']['labels'])) {
      $sync->setLabels($data['metadata']['labels']);
    }
    return $sync;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $object */
    $data = [
      'apiVersion' => 'environment.backups.shepherd/v1beta1',
      'kind' => 'Sync',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'source' => [
          'persistentVolumeClaim' => $object->getSource()->getPersistentVolumeClaim(),
          'secret' => $object->getSource()->getSecret(),
        ],
        'target' => [
          'persistentVolumeClaim' => $object->getTarget()->getPersistentVolumeClaim(),
          'secret' => $object->getTarget()->getSecret(),
        ],
      ],
    ];

    return $data;
  }

}
