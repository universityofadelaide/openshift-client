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
  protected $lastBackup;

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
   * Gets the value of lastBackup.
   *
   * @return string
   *   Value of lastBackup.
   */
  public function getLastBackup(): string {
    return $this->lastBackup;
  }

  /**
   * Sets the value of LastBackup.
   *
   * @param string $lastBackup
   *   The value for lastBackup.
   *
   * @return $this
   *   The calling class.
   */
  public function setLastBackup(string $lastBackup): ScheduledBackup {
    $this->lastBackup = $lastBackup;
    return $this;
  }

}
