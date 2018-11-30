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
   * The time the restore was created.
   *
   * @var string
   */
  protected $creationTimestamp;

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
   * @return $this
   *  The calling class.
   */
  public function setBackupName(string $backupName): Restore {
    $this->backupName = $backupName;
    return $this;
  }

  /**
   * Gets the value of creationTimestamp.
   *
   * @return string
   *  Value of creationTimestamp.
   */
  public function getCreationTimestamp(): string {
    return $this->creationTimestamp;
  }

  /**
   * Sets the value of creationTimestamp.
   *
   * @param string $creationTimestamp
   *  The value for creationTimestamp.
   *
   * @return $this
   *  The calling class.
   */
  public function setCreationTimestamp(string $creationTimestamp): Restore {
    $this->creationTimestamp = $creationTimestamp;
    return $this;
  }

}
