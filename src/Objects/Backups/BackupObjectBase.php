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

}
