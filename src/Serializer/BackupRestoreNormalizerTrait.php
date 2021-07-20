<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

/**
 * Trait for common functionality between backup/restore normalization.
 */
trait BackupRestoreNormalizerTrait {

  /**
   * Normalize the backup object's retention.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object
   *   The backup object.
   *
   * @return array
   *   Normalized retention.
   */
  protected function normalizeRetention(BackupObjectBase $object) {
    return [
      'maxNumber' => (int) $object->getRetention(),
    ];
  }

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
      'crontab' => $object->getSchedule(),
      'startingDeadlineSeconds' => $object->getStartingDeadlineSeconds(),
    ];
  }

  /**
   * Normalize the backup object's volumes.
   *
   * @param array $volumes
   *   The backup object's volumes.
   *
   * @return array
   *   Normalized volumes.
   */
  protected function normalizeVolumes(array $volumes) {
    $ret = [];
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupObjectBase $object */
    foreach ($volumes as $volumeId => $claimName) {
      $ret[$volumeId] = ['claimName' => $claimName];
    }
    return $ret;
  }

  /**
   * Normalize the backup object's mysqls.
   *
   * @param array $mysqls
   *   The backup object's mysqls.
   *
   * @return array
   *   Normalized mysqls.
   */
  protected function normalizeMysqls(array $mysqls) {
    return array_reduce($mysqls, function ($carry, Database $db) {
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
