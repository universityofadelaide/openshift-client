<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectBase;

/**
 * Value object for syncs.
 */
class Sync extends ObjectBase {

  /**
   * The array of volumes to backup.
   *
   * Keyed by an identifier, with the value being the PVC name.
   *
   * @var array
   */
  protected $backupVolumes = [];

  /**
   * The array of databases to backup.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   */
  protected $backupDatabases = [];

  /**
   * The array of volumes to restore.
   *
   * Keyed by an identifier, with the value being the PVC name.
   *
   * @var array
   */
  protected $restoreVolumes = [];

  /**
   * The array of databases to restore.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   */
  protected $restoreDatabases = [];

  /**
   * The site id.
   *
   * @var string
   */
  protected $site;

  /**
   * The environment id to backup.
   *
   * @var string
   */
  protected $backupEnv;

  /**
   * The environment id to restore.
   *
   * @var string
   */
  protected $restoreEnv;

  /**
   * The phase the backup is in.
   *
   * @var string
   */
  protected $backupPhase = '';

  /**
   * The phase the restore is in.
   *
   * @var string
   */
  protected $restorePhase = '';

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
   * Gets the value of BackupVolumes.
   *
   * @return array
   *   Value of BackupVolumes.
   */
  public function getBackupVolumes(): array {
    return $this->backupVolumes;
  }

  /**
   * Sets the value of BackupVolumes.
   *
   * @param array $backupVolumes
   *   The value for BackupVolumes.
   *
   * @return Sync
   *   The calling class.
   */
  public function setBackupVolumes(array $backupVolumes): Sync {
    $this->backupVolumes = $backupVolumes;
    return $this;
  }

  /**
   * Gets the value of BackupDatabases.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   *   Value of BackupDatabases.
   */
  public function getBackupDatabases(): array {
    return $this->backupDatabases;
  }

  /**
   * Sets the value of BackupDatabases.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[] $backupDatabases
   *   The value for BackupDatabases.
   *
   * @return Sync
   *   The calling class.
   */
  public function setBackupDatabases(array $backupDatabases): Sync {
    $this->backupDatabases = $backupDatabases;
    return $this;
  }

  /**
   * Gets the value of RestoreVolumes.
   *
   * @return array
   *   Value of RestoreVolumes.
   */
  public function getRestoreVolumes(): array {
    return $this->restoreVolumes;
  }

  /**
   * Sets the value of RestoreVolumes.
   *
   * @param array $restoreVolumes
   *   The value for RestoreVolumes.
   *
   * @return Sync
   *   The calling class.
   */
  public function setRestoreVolumes(array $restoreVolumes): Sync {
    $this->restoreVolumes = $restoreVolumes;
    return $this;
  }

  /**
   * Gets the value of RestoreDatabases.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[]
   *   Value of RestoreDatabases.
   */
  public function getRestoreDatabases(): array {
    return $this->restoreDatabases;
  }

  /**
   * Sets the value of RestoreDatabases.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Database[] $restoreDatabases
   *   The value for RestoreDatabases.
   *
   * @return Sync
   *   The calling class.
   */
  public function setRestoreDatabases(array $restoreDatabases): Sync {
    $this->restoreDatabases = $restoreDatabases;
    return $this;
  }

  /**
   * Gets the value of Site.
   *
   * @return string
   *   Value of Site.
   */
  public function getSite(): string {
    return $this->site;
  }

  /**
   * Sets the value of Site.
   *
   * @param string $site
   *   The value for Site.
   *
   * @return Sync
   *   The calling class.
   */
  public function setSite(string $site): Sync {
    $this->site = $site;
    return $this;
  }

  /**
   * Gets the value of BackupEnv.
   *
   * @return string
   *   Value of BackupEnv.
   */
  public function getBackupEnv(): string {
    return $this->backupEnv;
  }

  /**
   * Sets the value of BackupEnv.
   *
   * @param string $backupEnv
   *   The value for BackupEnv.
   *
   * @return Sync
   *   The calling class.
   */
  public function setBackupEnv(string $backupEnv): Sync {
    $this->backupEnv = $backupEnv;
    return $this;
  }

  /**
   * Gets the value of RestoreEnv.
   *
   * @return string
   *   Value of RestoreEnv.
   */
  public function getRestoreEnv(): string {
    return $this->restoreEnv;
  }

  /**
   * Sets the value of RestoreEnv.
   *
   * @param string $restoreEnv
   *   The value for RestoreEnv.
   *
   * @return Sync
   *   The calling class.
   */
  public function setRestoreEnv(string $restoreEnv): Sync {
    $this->restoreEnv = $restoreEnv;
    return $this;
  }

  /**
   * Gets the value of BackupPhase.
   *
   * @return string
   *   Value of BackupPhase.
   */
  public function getBackupPhase(): string {
    return $this->backupPhase;
  }

  /**
   * Sets the value of BackupPhase.
   *
   * @param string $backupPhase
   *   The value for BackupPhase.
   *
   * @return Sync
   *   The calling class.
   */
  public function setBackupPhase(string $backupPhase): Sync {
    $this->backupPhase = $backupPhase;
    return $this;
  }

  /**
   * Gets the value of RestorePhase.
   *
   * @return string
   *   Value of RestorePhase.
   */
  public function getRestorePhase(): string {
    return $this->restorePhase;
  }

  /**
   * Sets the value of RestorePhase.
   *
   * @param string $restorePhase
   *   The value for RestorePhase.
   *
   * @return Sync
   *   The calling class.
   */
  public function setRestorePhase(string $restorePhase): Sync {
    $this->restorePhase = $restorePhase;
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
   * @return Sync
   *   The calling class.
   */
  public function setStartTimestamp(string $startTimestamp): Sync {
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
   * @return Sync
   *   The calling class.
   */
  public function setCompletionTimestamp(string $completionTimestamp): Sync {
    $this->completionTimestamp = $completionTimestamp;
    return $this;
  }

}
