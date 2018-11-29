<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Value object for scheduled backups.
 */
class ScheduledBackup extends BackupObjectBase {

  /**
   * The TTL for backups running on this schedule.
   *
   * @var string
   */
  protected $ttl = '720h0m0s';

  /**
   * An array of labels that must be matched to be included in the backups.
   *
   * @var array
   */
  protected $matchLabels = [];

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
   * Factory method for creating a new ScheduledBackup.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
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
  public function setTtl(string $ttl): ScheduledBackup {
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
  public function setMatchLabels(array $matchLabels): ScheduledBackup {
    $this->matchLabels = $matchLabels;
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
