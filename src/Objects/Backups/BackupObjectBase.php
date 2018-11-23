<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * A base class for backup and restore objects.
 */
abstract class BackupObjectBase {

  /**
   * The name of the restore.
   *
   * @var string
   */
  protected $name;

  /**
   * An array of labels.
   *
   * @var array
   */
  protected $labels = [];

  /**
   * The phase the backup is in.
   *
   * @var string
   */
  protected $phase;

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
  public function setName(string $name) {
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
  public function setLabels(array $labels) {
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
  public function setLabel(string $key, string $value) {
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
  public function setPhase(string $phase) {
    $this->phase = $phase;
    return $this;
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

}
