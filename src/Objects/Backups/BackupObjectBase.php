<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * A base class for backup and restore objects.
 */
abstract class BackupObjectBase extends ObjectBase {

  /**
   * The phase the object is in.
   *
   * @var string
   */
  protected $phase = '';

  /**
   * The time the backup was started.
   *
   * @var string
   */
  protected $startTimestamp = '';

  /**
   * The time the backup completed.
   *
   * @var string
   */
  protected $completionTimestamp = '';

  /**
   * The array of volumes to backup/restore.
   *
   * Keyed by an identifier, with the value being the PVC name.
   *
   * @var array
   */
  protected $volumes = [];

  /**
   * The array of databases to backup/restore.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   */
  protected $databases = [];

  /**
   * Gets the value of phase.
   *
   * @return string
   *   Value of phase.
   */
  public function getPhase(): string {
    return $this->phase;
  }

  /**
   * Sets the value of phase.
   *
   * @param string $phase
   *   The value for phase.
   *
   * @return $this
   *   The calling class.
   */
  public function setPhase(string $phase) {
    $this->phase = $phase;
    return $this;
  }

  /**
   * Check if the object phase is completed.
   *
   * @return bool
   *   Whether the object phase is completed.
   */
  public function isCompleted(): bool {
    return $this->getPhase() === Phase::COMPLETED;
  }

  /**
   * Gets the value of startTimestamp.
   *
   * @return string
   *   Value of startTimestamp.
   */
  public function getStartTimestamp(): string {
    return $this->startTimestamp;
  }

  /**
   * Sets the value of startTimestamp.
   *
   * @param string $startTimestamp
   *   The value for startTimestamp.
   *
   * @return $this
   *   The calling class.
   */
  public function setStartTimestamp(string $startTimestamp): BackupObjectBase {
    $this->startTimestamp = $startTimestamp;
    return $this;
  }

  /**
   * Gets the value of completionTimestamp.
   *
   * @return string
   *   Value of completionTimestamp.
   */
  public function getCompletionTimestamp(): string {
    return $this->completionTimestamp;
  }

  /**
   * Sets the value of completionTimestamp.
   *
   * @param string $completionTimestamp
   *   The value for completionTimestamp.
   *
   * @return $this
   *   The calling class.
   */
  public function setCompletionTimestamp(string $completionTimestamp): BackupObjectBase {
    $this->completionTimestamp = $completionTimestamp;
    return $this;
  }

  /**
   * Gets the value of Volumes.
   *
   * @return array
   *   Value of Volumes.
   */
  public function getVolumes(): array {
    return $this->volumes;
  }

  /**
   * Sets the value of Volumes.
   *
   * @param array $volumes
   *   The value for Volumes.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function setVolumes(array $volumes): BackupObjectBase {
    $this->volumes = $volumes;
    return $this;
  }

  /**
   * Gets the value of Databases.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   *   Value of Databases.
   */
  public function getDatabases(): array {
    return $this->databases;
  }

  /**
   * Sets the value of Databases.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[] $databases
   *   The value for Databases.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function setDatabases(array $databases): BackupObjectBase {
    $this->databases = $databases;
    return $this;
  }

  /**
   * Adds a database.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Database $db
   *   The db to add.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function addDatabase(Database $db): BackupObjectBase {
    $this->databases[] = $db;
    return $this;
  }

}
