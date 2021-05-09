<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Value object for scheduled backups.
 */
class ScheduledBackup extends BackupObjectBase {

  /**
   * Schedule as a Cron expression defining when to run the backups.
   *
   * @var string
   */
  protected $schedule;

  /**
   * Retention as a number of scheduled backups to retain.
   *
   * @var int
   */
  protected $retention;

  /**
   * Timestamp for when the last backup ran.
   *
   * @var string
   */
  protected $lastExecuted;

  /**
   * Schedule requires a starting deadline in seconds.
   *
   * @var int
   */
  protected $startingDeadlineSeconds = 3600;

  /**
   * @return string
   */
  public function getPhase(): string {
    return $this->phase;
  }

  /**
   * @param string $phase
   *
   * @return ScheduledBackup
   */
  public function setPhase(string $phase): ScheduledBackup {
    $this->phase = $phase;
    return $this;
  }

  /**
   * Gets the value of schedule.
   *
   * @return string
   *   Value of schedule.
   */
  public function getSchedule(): string {
    return $this->schedule;
  }

  /**
   * Sets the value of schedule.
   *
   * @param string $schedule
   *   The value for schedule.
   *
   * @return $this
   *   The calling class.
   */
  public function setSchedule(string $schedule): ScheduledBackup {
    $this->schedule = $schedule;
    return $this;
  }

  /**
   * Gets the value of schedule.
   *
   * @return string
   *   Value of schedule.
   */
  public function getRetention(): string {
    return $this->retention;
  }

  /**
   * Sets the value of schedule.
   *
   * @param string $schedule
   *   The value for schedule.
   *
   * @return $this
   *   The calling class.
   */
  public function setRetention(string $retention): ScheduledBackup {
    $this->retention = $retention;
    return $this;
  }

  /**
   * Gets the value of lastExecuted.
   *
   * @return string
   *   Value of lastExecuted.
   */
  public function getLastExecuted(): string {
    return $this->lastExecuted;
  }

  /**
   * Sets the value of lastExecuted.
   *
   * @param string $lastExecuted
   *   The value for lastExecuted.
   *
   * @return $this
   *   The calling class.
   */
  public function setLastExecuted(string $lastExecuted): ScheduledBackup {
    $this->lastExecuted = $lastExecuted;
    return $this;
  }

  /**
   * Gets the value of startingDeadlineSeconds.
   *
   * @return int
   */
  public function getStartingDeadlineSeconds(): int {
    return $this->startingDeadlineSeconds;
  }

  /**
   * Sets the value of startingDeadlineSeconds.
   *
   * @param int $startingDeadlineSeconds
   *
   * @return ScheduledBackup
   */
  public function setStartingDeadlineSeconds(int $startingDeadlineSeconds
  ): ScheduledBackup {
    $this->startingDeadlineSeconds = $startingDeadlineSeconds;
    return $this;
  }

}
