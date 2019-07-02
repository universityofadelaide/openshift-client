<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Backup.
 */
class Backup extends BackupObjectBase {

  /**
   * An array of annotations to apply to this backup.
   *
   * @var array
   */
  protected $annotations = [];

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
   * The array of volumes to backup.
   *
   * Keyed by an identifier, with the value being the PVC name.
   *
   * @var array
   */
  protected $volumes = [];

  /**
   * The array of databases to backup.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   */
  protected $databases = [];

  /**
   * The restic ID.
   *
   * @var string
   */
  protected $resticId;

  /**
   * Get a single annotation.
   *
   * @param string $key
   *   The key for the annotation.
   *
   * @return string|bool
   *   The annotation value or FALSE.
   */
  public function getAnnotation(string $key): string {
    return isset($this->getAnnotations()[$key]) ? $this->getAnnotations()[$key] : FALSE;
  }

  /**
   * Gets the value of annotations.
   *
   * @return array
   *   Value of annotations.
   */
  public function getAnnotations(): array {
    return $this->annotations;
  }

  /**
   * Check if this backup has any annotations.
   *
   * @return bool
   *   Whether this backup has annotations.
   */
  public function hasAnnotations(): bool {
    return !empty($this->getAnnotations());
  }

  /**
   * Set a single annotation.
   *
   * @param string $key
   *   The key for the annotation.
   * @param string $value
   *   The value for the annotation.
   *
   * @return Backup
   *   The calling class.
   */
  public function setAnnotation(string $key, string $value): Backup {
    $this->annotations[$key] = $value;
    return $this;
  }

  /**
   * Sets the value of annotations.
   *
   * @param array $annotations
   *   The value for annotations.
   *
   * @return Backup
   *   The calling class.
   */
  public function setAnnotations(array $annotations): Backup {
    $this->annotations = $annotations;
    return $this;
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
  public function setStartTimestamp(string $startTimestamp): Backup {
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
  public function setCompletionTimestamp(string $completionTimestamp): Backup {
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
   * Gets the value of ResticId.
   *
   * @return string
   *   Value of ResticId.
   */
  public function getResticId(): string {
    return $this->resticId;
  }

  /**
   * Sets the value of ResticId.
   *
   * @param string $resticId
   *   The value for ResticId.
   *
   * @return BackupObjectBase
   *   The calling class.
   */
  public function setResticId(string $resticId): BackupObjectBase {
    $this->resticId = $resticId;
    return $this;
  }

}
