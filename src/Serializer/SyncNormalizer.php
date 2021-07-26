<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use Symfony\Component\Serializer\Encoder\NormalizationAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

/**
 * Serializer for Sync objects.
 */
class SyncNormalizer extends BaseNormalizer implements NormalizationAwareInterface {

  use BackupRestoreNormalizerTrait;
  use NormalizerAwareTrait;

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = Sync::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $sync */
    $sync = Sync::create();
    $sync->setName($data['metadata']['name'])
      ->setLabels($data['metadata']['labels'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setSite($data['spec']['site'])
      ->setBackupEnv($data['spec']['backupEnv'])
      ->setRestoreEnv($data['spec']['restoreEnv'])
      ->setBackupPhase($data['status']['backupPhase'] ?? '')
      ->setRestorePhase($data['status']['restorePhase'] ?? '')
      ->setStartTimeStamp($data['status']['startTime'] ?? '')
      ->setCompletionTimeStamp($data['status']['completionTime'] ?? '');

    $backupDbs = [];
    foreach ($data['spec']['backupSpec']['mysql'] as $id => $dbSpec) {
      $backupDbs[] = Database::createFromValues($id, $dbSpec['secret']['name'], $dbSpec['secret']['keys']);
    }
    $sync->setBackupDatabases($backupDbs);
    $restoreDbs = [];
    foreach ($data['spec']['restoreSpec']['mysql'] as $id => $dbSpec) {
      $restoreDbs[] = Database::createFromValues($id, $dbSpec['secret']['name'], $dbSpec['secret']['keys']);
    }
    $sync->setRestoreDatabases($restoreDbs);

    $backupVolumes = [];
    foreach ($data['spec']['backupSpec']['volumes'] as $id => $volumeSpec) {
      $backupVolumes[$id] = $volumeSpec['claimName'];
    }
    $sync->setBackupVolumes($backupVolumes);

    $restoreVolumes = [];
    foreach ($data['spec']['restoreSpec']['volumes'] as $id => $volumeSpec) {
      $restoreVolumes[$id] = $volumeSpec['claimName'];
    }
    $sync->setRestoreVolumes($restoreVolumes);

    return $sync;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $object */
    $data = [
      'apiVersion' => 'extension.shepherd/v1',
      'kind' => 'Sync',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'site' => $object->getSite(),
        'backupEnv' => $object->getBackupEnv(),
        'restoreEnv' => $object->getRestoreEnv(),
        'backupSpec' => [
          'volumes' => $this->normalizeVolumes($object->getBackupVolumes()),
          'mysql' => $this->normalizeMysqls($object->getBackupDatabases()),
        ],
        'restoreSpec' => [
          'volumes' => $this->normalizeVolumes($object->getRestoreVolumes()),
          'mysql' => $this->normalizeMysqls($object->getRestoreDatabases()),
        ],
      ],
    ];

    return $data;
  }

}
