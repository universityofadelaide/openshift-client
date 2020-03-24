<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

/**
 * Trait for common functionality between backup/restore normalization.
 */
trait BackupRestoreNormalizerTrait {

  /**
   * Normalize the backup object's schedule.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object
   *   The backup object.
   *
   * @return array
   *   Normalized schedule.
   */
  protected function normalizeSchedule(BackupObjectBase $object) {
    return [
      'crontab' => $object->getSchedule()
    ];
  }

  /**
   * Normalize the backup object's volumes.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object
   *   The backup object.
   *
   * @return array
   *   Normalized volumes.
   */
  protected function normalizeVolumes(BackupObjectBase $object) {
    $volumes = [];
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object */
    foreach ($object->getVolumes() as $volumeId => $claimName) {
      $volumes[$volumeId] = ['claimName' => $claimName];
    }

    return $volumes;
  }

  /**
   * Normalize the backup object's mysqls.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object
   *   The backup object.
   *
   * @return array
   *   Normalized mysqls.
   */
  protected function normalizeMysqls(BackupObjectBase $object) {
    return array_reduce($object->getDatabases(), function ($carry, Database $db) {
      $carry[$db->getId()] = [
        'secret' => [
          'name' => $db->getSecretName(),
          'keys' => $db->getSecretKeys(),
        ],
      ];
      return $carry;
    }, []);
  }

}
