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
   * Timestamp for when the last backup ran.
   *
   * @var string
   */
  protected $lastExecuted;

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

}
