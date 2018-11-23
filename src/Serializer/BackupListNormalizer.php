<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;

/**
 * Serializer for BackupList objects.
 */
class BackupListNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = BackupList::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $backups = BackupList::create();

    foreach ($data['items'] as $backupData) {
      $backups->addBackup($this->serializer->denormalize($backupData, Backup::class));
    }

    return $backups;
  }

}
