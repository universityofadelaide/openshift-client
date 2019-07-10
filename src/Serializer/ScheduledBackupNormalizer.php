<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;

/**
 * Serializer for ScheduledBackup objects.
 */
class ScheduledBackupNormalizer extends BaseNormalizer {

  use BackupRestoreNormalizerTrait;

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = ScheduledBackup::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup $schedule */
    $schedule = ScheduledBackup::create();
    $schedule->setName($data['metadata']['name'])
      ->setLabels($data['metadata']['labels'])
      ->setSchedule($data['spec']['schedule'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setLastExecuted($data['status']['lastExecutedTime'] ?? '');
    return $schedule;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup $object */
    $data = [
      'apiVersion' => 'extension.shepherd/v1',
      'kind' => 'BackupScheduled',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'schedule' => $object->getSchedule(),
        'volumes' => $this->normalizeVolumes($object),
        'mysql' => $this->normalizeMysqls($object),
      ],
    ];

    return $data;
  }

}
