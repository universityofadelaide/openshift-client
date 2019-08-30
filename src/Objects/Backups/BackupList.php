<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectListBase;

/**
 * Defines a value object representing a BackupList.
 */
class BackupList extends ObjectListBase {

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
   *   The list of backups.
   */
  public function getBackups(): array {
    return $this->backups;
  }

  /**
   * Gets a list of backups ordered by created time.
   *
   * @param string $operator
   *   Which way to order the list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *   The list of sorted backups.
   */
  public function getBackupsByCreatedTime($operator = 'DESC'): array {
    return $this->sortObjectsByCreationTime($this->getBackups(), $operator);
  }

  /**
   * Sorts an array of backups by start time.
   *
   * @param array $backups
   *   The array of backups.
   * @param string $operator
   *   The sort operator.
   *
   * @return array
   *   The sorted array.
   */
  protected function sortBackupsByStartTime(array $backups, string $operator) {
    usort($backups, function (Backup $a, Backup $b) use ($operator) {
      return $operator === 'DESC' ? $a->getStartTimestamp() < $b->getStartTimestamp() : $a->getStartTimestamp() > $b->getStartTimestamp();
    });
    return $backups;
  }

  /**
   * Gets a list of backups ordered by start time.
   *
   * @param string $operator
   *   Which way to order the list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *   The list of backups.
   */
  public function getBackupsByStartTime($operator = 'DESC'): array {
    return $this->sortBackupsByStartTime($this->getBackups(), $operator);
  }

  /**
   * Gets the backup list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *   The list of backups.
   */
  public function getCompletedBackups(): array {
    return array_filter($this->getBackups(), function (Backup $backup) {
      return $backup->isCompleted();
    });
  }

  /**
   * Gets a list of completed backups ordered by start time.
   *
   * @param string $operator
   *   Which way to order the list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup[]
   *   The list of backups.
   */
  public function getCompletedBackupsByStartTime($operator = 'DESC'): array {
    return $this->sortBackupsByStartTime($this->getCompletedBackups(), $operator);
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
