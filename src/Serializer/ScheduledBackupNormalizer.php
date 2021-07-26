<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
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
      ->setSchedule($data['spec']['schedule']['crontab'])
      ->setRetention($data['spec']['retention']['maxNumber'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setLastExecuted($data['status']['lastExecutedTime'] ?? '');

    foreach ($data['spec']['mysql'] as $id => $dbSpec) {
      $schedule->addDatabase(Database::createFromValues($id, $dbSpec['secret']['name'], $dbSpec['secret']['keys']));
    }

    foreach ($data['spec']['volumes'] as $id => $volumeSpec) {
      $schedule->addVolume($id, $volumeSpec['claimName']);
    }

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
        'retention' => $this->normalizeRetention($object),
        'schedule' => $this->normalizeSchedule($object),
        'volumes' => $this->normalizeVolumes($object->getVolumes()),
        'mysql' => $this->normalizeMysqls($object->getDatabases()),
      ],
    ];

    return $data;
  }

}
