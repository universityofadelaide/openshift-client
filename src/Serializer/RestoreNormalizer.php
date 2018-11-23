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
    $backup = Restore::create();
    $backup->setName($data['metadata']['name'])
      ->setBackupName($data['spec']['backupName'])
      ->setLabels($data['metadata']['labels']);
    if (isset($data['status']['phase'])) {
      $backup->setPhase($data['status']['phase']);
    }
    return $backup;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $object */
    $data = [
      'apiVersion' => 'ark.heptio.com/v1',
      'kind' => 'Restore',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' =>  $object->getName(),
        'namespace' => 'heptio-ark',
      ],
      'spec' => [
        'backupName' => $object->getBackupName(),
      ],
    ];

    return $data;
  }

}
