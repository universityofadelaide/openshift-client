<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Backup.
 */
class Backup {

  /**
   * The name of the backup.
   *
   * @var string
   */
  protected $name;

  /**
   * The array of labels to apply to this backup.
   *
   * @var array
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
   * The phase the backup is in.
   *
   * @var string
   */
  protected $phase;

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
   * Gets the value of name.
   *
   * @return string
   *   Value of name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Sets the name.
   *
   * @param string $name
   *   The name of the backup.
   *
   *  @return $this
   *   The calling class.
   */
  public function setName(string $name): Backup {
    $this->name = $name;
    return $this;
  }

  /**
   * Gets the value of labels.
   *
   * @return array
   *  Value of labels.
   */
  public function getLabels(): array {
    return $this->labels;
  }

  /**
   * Sets the array of labels.
   *
   * @param array $labels
   *   An array of labels.
   *
   * @return $this
   *   The calling class.
   */
  public function setLabels(array $labels): Backup {
    $this->labels = $labels;
    return $this;
  }

  /**
   * Set a single label.
   *
   * @param string $key
   *   The key of the label.
   * @param string $value
   *   The value of the label.
   *
   * @return $this
   *   The calling class.
   */
  public function setLabel(string $key, string $value): Backup {
    $this->labels[$key] = $value;
    return $this;
  }

  /**
   * Get a single label.
   *
   * @param string $key
   *   The key of the label.
   *
   * @return string|bool
   *   The label value, or FALSE if it doesn't exist.
   */
  public function getLabel(string $key): string {
    return isset($this->getLabels()[$key]) ? $this->getLabels()[$key] : FALSE;
  }

  /**
   * Gets the value of hooks.
   *
   * @return \Drupal\shp_backup\Backups\Hook[]
   *  Value of hooks.
   */
  public function getHooks(): array {
    return $this->hooks;
  }

  /**
   * Sets the value for hooks.
   *
   * @param \Drupal\shp_backup\Backups\Hook[] $hooks
   *   An array of hook objects.
   *
   * @return $this
   *  The calling class.
   */
  public function setHooks(array $hooks): Backup {
    $this->hooks = $hooks;
    return $this;
  }

  /**
   * Gets the value of ttl.
   *
   * @return string
   *  Value of ttl.
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
   *  The calling class.
   */
  public function setTtl(string $ttl): Backup {
    $this->ttl = $ttl;
    return $this;
  }

  /**
   * Gets the value of matchLabels.
   *
   * @return array
   *  Value of matchLabels.
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
   *  The calling class.
   */
  public function setMatchLabels(array $matchLabels): Backup {
    $this->matchLabels = $matchLabels;
    return $this;
  }

  /**
   * Gets the value of phase.
   *
   * @return string
   *  Value of phase.
   */
  public function getPhase(): string {
    return $this->phase;
  }

  /**
   * Sets the value of phase.
   *
   * @param string $phase
   *  The value for phase.
   *
   * @return $this
   *  The calling class.
   */
  public function setPhase(string $phase): Backup {
    $this->phase = $phase;
    return $this;
  }

  /**
   * Gets the value of startTimestamp.
   *
   * @return string
   *  Value of startTimestamp.
   */
  public function getStartTimestamp(): string {
    return $this->startTimestamp;
  }

  /**
   * Sets the value of startTimestamp.
   *
   * @param string $startTimestamp
   *  The value for startTimestamp.
   *
   * @return $this
   *  The calling class.
   */
  public function setStartTimestamp(string $startTimestamp): Backup {
    $this->startTimestamp = $startTimestamp;
    return $this;
  }

  /**
   * Gets the value of completionTimestamp.
   *
   * @return string
   *  Value of completionTimestamp.
   */
  public function getCompletionTimestamp(): string {
    return $this->completionTimestamp;
  }

  /**
   * Check if the backup is completed.
   *
   * @return bool
   *   Whether the backup has completed.
   */
  public function isCompleted(): bool {
    return $this->getPhase() === Phase::COMPLETED;
  }

  /**
   * Sets the value of completionTimestamp.
   *
   * @param string $completionTimestamp
   *  The value for completionTimestamp.
   *
   * @return $this
   *  The calling class.
   */
  public function setCompletionTimestamp(string $completionTimestamp): Backup {
    $this->completionTimestamp = $completionTimestamp;
    return $this;
  }

  /**
   * Gets the value of expires.
   *
   * @return string
   *  Value of expires.
   */
  public function getExpires(): string {
    return $this->expires;
  }

  /**
   * Sets the value of expires.
   *
   * @param string $expires
   *  The value for expires.
   *
   * @return $this
   *  The calling class.
   */
  public function setExpires(string $expires): Backup {
    $this->expires = $expires;
    return $this;
  }

}
