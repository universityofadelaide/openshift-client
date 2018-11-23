<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Restore.
 */
class Restore extends BackupObjectBase {

  /**
   * The backup name that this restore is restoring from.
   *
   * @var string
   */
  protected $backupName;

  /**
   * The name of the restore.
   *
   * We set this to an empty string by default so ark generates this for us.
   *
   * @var string
   */
  protected $name = '';

  /**
   * Factory method for creating a new Backup.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
  }

  /**
   * Gets the value of backupName.
   *
   * @return string
   *  Value of backupName.
   */
  public function getBackupName(): string {
    return $this->backupName;
  }

  /**
   * Sets the value of backupName.
   *
   * @param string $backupName
   *  The value for backupName.
   *
   * @return Restore
   *  The calling class.
   */
  public function setBackupName(string $backupName): Restore {
    $this->backupName = $backupName;
    return $this;
  }

}
