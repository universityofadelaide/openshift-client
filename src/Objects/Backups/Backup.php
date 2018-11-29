<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Backup.
 */
class Backup extends BackupObjectBase {

  /**
   * The name of the annotation on pods to signal ark to backup its volumes.
   */
  const VolumesToBackupAnnotation = 'backup.ark.heptio.com/backup-volumes';

  /**
   * An array of annotations to apply to this backup.
   *
   * @var array
   */
  protected $annotations = [];

  /**
   * {@inheritdoc}
   */
  protected $labels = [
    'ark.heptio.com/storage-location' => 'default',
  ];

  /**
   * An array of hooks to run during this backup.
   *
   * @var \Drupal\shp_backup\Backups\Hook[]
   */
  protected $hooks = [];

  /**
   * The TTL for this backup.
   *
   * @var string
   */
  protected $ttl = '720h0m0s';

  /**
   * An array of labels that must be matched to be included in the backup.
   *
   * @var array
   */
  protected $matchLabels = [];

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
   * The time the backup expires.
   *
   * @var string
   */
  protected $expires;

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
   * Gets the value of ttl.
   *
   * @return string
   *   Value of ttl.
   */
  public function getTtl(): string {
    return $this->ttl;
  }

  /**
   * Sets the TTL.
   *
   * @param string $ttl
   *   The ttl.
   *
   * @return $this
   *   The calling class.
   */
  public function setTtl(string $ttl): Backup {
    $this->ttl = $ttl;
    return $this;
  }

  /**
   * Gets the value of matchLabels.
   *
   * @return array
   *   Value of matchLabels.
   */
  public function getMatchLabels(): array {
    return $this->matchLabels;
  }

  /**
   * Sets the match labels.
   *
   * @param array $matchLabels
   *   An array of labels.
   *
   * @return $this
   *   The calling class.
   */
  public function setMatchLabels(array $matchLabels): Backup {
    $this->matchLabels = $matchLabels;
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
   * Gets the value of expires.
   *
   * @return string
   *   Value of expires.
   */
  public function getExpires(): string {
    return $this->expires;
  }

  /**
   * Sets the value of expires.
   *
   * @param string $expires
   *   The value for expires.
   *
   * @return $this
   *   The calling class.
   */
  public function setExpires(string $expires): Backup {
    $this->expires = $expires;
    return $this;
  }

}
