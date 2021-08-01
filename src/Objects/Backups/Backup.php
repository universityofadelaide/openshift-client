<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Backup.
 */
class Backup extends BackupObjectBase {

  /**
   * The name of the label to determine whether the backup is manual.
   */
  const MANUAL_LABEL = 'is-manual';

  /**
   * The name of the label to determine whether the backup is sync.
   */
  const SYNC_LABEL = 'is-sync';

  /**
   * An array of annotations to apply to this backup.
   *
   * @var array
   */
  protected $annotations = [];

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

  /**
   * Return whether the backup was manually triggered.
   *
   * @return bool
   *   TRUE if the backup was manually triggered.
   */
  public function isManual(): bool {
    return (int) $this->getLabel(self::MANUAL_LABEL) === 1;
  }

  /**
   * Return whether the backup was manually triggered.
   *
   * @return bool
   *   TRUE if the backup was manually triggered.
   */
  public function isSync(): bool {
    return (int) $this->getLabel(self::SYNC_LABEL) === 1;
  }
}
