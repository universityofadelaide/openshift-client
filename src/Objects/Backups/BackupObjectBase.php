<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectBase;

/**
 * A base class for backup and restore objects.
 */
abstract class BackupObjectBase extends ObjectBase {

  /**
   * Defines the annotation to store a backups friendly name on.
   */
  const FRIENDLY_NAME_ANNOTATION = 'backups.shepherd/friendly-name';

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
   * The time the backup was deleted.
   *
   * @var string
   */
  protected $deletionTimestamp = '';

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
   * Gets the value of DeletionTimestamp.
   *
   * @return string
   *   Value of DeletionTimestamp.
   */
  public function getDeletionTimestamp(): string {
    return $this->deletionTimestamp;
  }

  /**
   * Sets the value of DeletionTimestamp.
   *
   * @param string $deletionTimestamp
   *   The value for DeletionTimestamp.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function setDeletionTimestamp(string $deletionTimestamp): BackupObjectBase {
    $this->deletionTimestamp = $deletionTimestamp;
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
   * Add a new volume.
   *
   * @param string $id
   *   The volume name.
   * @param string $claimName
   *   The claim name.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function addVolume($id, $claimName): BackupObjectBase {
    $this->volumes[$id] = $claimName;
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

  /**
   * Returns the friendly name of the backup.
   *
   * @return string
   *   The friendly name if set, otherwise the backup name.
   */
  public function getFriendlyName(): string {
    return $this->getAnnotation(self::FRIENDLY_NAME_ANNOTATION) ?: $this->getName();
  }

}
