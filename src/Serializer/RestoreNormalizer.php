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
      ->setLabels($data['metadata']['labels'])
      ->setPhase($data['status']['phase'] ?? '')
      ->setStartTimeStamp($data['status']['startTime'] ?? '')
      ->setCompletionTimeStamp($data['status']['completionTime'] ?? '');
    return $restore;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $object */
    $data = [
      'apiVersion' => 'extension.shepherd/v1',
      'kind' => 'Restore',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'volumes' => $this->normalizeVolumes($object->getVolumes()),
        'mysql' => $this->normalizeMysqls($object->getDatabases()),
        'backupName' => $object->getBackupName(),
      ],
    ];

    return $data;
  }

}
