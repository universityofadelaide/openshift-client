<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Sync.
 */
class Sync extends BackupObjectBase {

  /**
   * The source environment.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   */
  protected $source;

  /**
   * The target environment.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   */
  protected $target;

  /**
   * The time the sync was created.
   *
   * @var string
   */
  protected $creationTimestamp;

  /**
   * Create a sync object from a source and target env.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment $source
   *   The source env.
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment $target
   *   The target env.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync
   *   The sync object.
   */
  public static function createFromSourceAndTarget(SyncEnvironment $source, SyncEnvironment $target) {
    return (new static())->setSource($source)->setTarget($target);
  }

  /**
   * Gets the value of Source.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   *   Value of Source.
   */
  public function getSource(): SyncEnvironment {
    return $this->source;
  }

  /**
   * Sets the value of Source.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment $source
   *   The value for Source.
   *
   * @return Sync
   *   The calling class.
   */
  public function setSource(SyncEnvironment $source): Sync {
    $this->source = $source;
    return $this;
  }

  /**
   * Gets the value of Target.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   *   Value of Target.
   */
  public function getTarget(): SyncEnvironment {
    return $this->target;
  }

  /**
   * Sets the value of Target.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment $target
   *   The value for Target.
   *
   * @return Sync
   *   The calling class.
   */
  public function setTarget(SyncEnvironment $target): Sync {
    $this->target = $target;
    return $this;
  }

  /**
   * Gets the value of creationTimestamp.
   *
   * @return string
   *   Value of creationTimestamp.
   */
  public function getCreationTimestamp(): string {
    return $this->creationTimestamp;
  }

  /**
   * Sets the value of creationTimestamp.
   *
   * @param string $creationTimestamp
   *   The value for creationTimestamp.
   *
   * @return Sync
   *   The calling class.
   */
  public function setCreationTimestamp(string $creationTimestamp): Sync {
    $this->creationTimestamp = $creationTimestamp;
    return $this;
  }

}
