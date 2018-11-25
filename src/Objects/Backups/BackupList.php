<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a BackupList.
 */
class BackupList {

  /**
   * The list of backups.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   */
  protected $backups = [];

  /**
   * Factory method for creating a new BackupList.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
  }

  /**
   * Gets the backup list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *  The list of backups.
   */
  public function getBackups(): array {
    return $this->backups;
  }

  /**
   * Gets the backup list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *  The list of backups.
   */
  public function getCompletedBackups(): array {
    return array_filter($this->backups, function (Backup $backup) {
      return $backup->getPhase() === Phase::COMPLETED;
    });
  }

  /**
   * Adds a backup to the list.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $backup
   *   The backup to add to the list.
   *
   * @return $this
   *   The calling class.
   */
  public function addBackup(Backup $backup): BackupList {
    $this->backups[] = $backup;
    return $this;
  }

  /**
   * Gets the number of backups.
   *
   * @return int
   *   The number of backups in this list.
   */
  public function getBackupCount(): int {
    return count($this->getBackups());
  }

  /**
   * Checks there are backups in this list.
   *
   * @return bool
   *   TRUE if there are any backups.
   */
  public function hasBackups():bool {
    return (bool) $this->getBackupCount();
  }

}
