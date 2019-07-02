<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;

/**
 * Serializer for Restore objects.
 */
class RestoreNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = Restore::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $restore */
    $restore = Restore::create();
    $restore->setName($data['metadata']['name'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setBackupName($data['spec']['backupName'])
      ->setLabels($data['metadata']['labels']);
    if (isset($data['status']['phase'])) {
      $restore->setPhase($data['status']['phase']);
    }
    return $restore;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $object */
    $data = [
      'apiVersion' => 'extensions.shepherd.io/v1beta1',
      'kind' => 'Restore',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'backupName' => $object->getBackupName(),
      ],
    ];

    return $data;
  }

}
