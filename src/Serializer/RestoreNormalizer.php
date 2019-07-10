<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;

/**
 * Serializer for Restore objects.
 */
class RestoreNormalizer extends BaseNormalizer {

  use BackupRestoreNormalizerTrait;

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
    if (isset($data['status']['startTime'])) {
      $restore->setStartTimestamp($data['status']['startTime']);
    }
    if (isset($data['status']['completionTime'])) {
      $restore->setCompletionTimestamp($data['status']['completionTime']);
    }
    return $restore;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    $volumes = [];
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $object */
    foreach ($object->getVolumes() as $volumeId => $claimName) {
      $volumes[$volumeId] = ['claimName' => $claimName];
    }
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $object */
    $data = [
      'apiVersion' => 'extension.shepherd/v1',
      'kind' => 'Restore',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'volumes' => $this->normalizeVolumes($object),
        'mysql' => $this->normalizeMysqls($object),
        'backupName' => $object->getBackupName(),
      ],
    ];

    return $data;
  }

}
