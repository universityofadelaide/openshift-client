<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectListBase;

/**
 * Defines a value object representing a SyncList.
 */
class SyncList extends ObjectListBase {

  /**
   * The list of backups.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync[]
   */
  protected $syncs = [];

  /**
   * Factory method for creating a new RestoreList.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
  }

  /**
   * Gets the sync list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync[]
   *   The list of syncs.
   */
  public function getSyncs(): array {
    return $this->syncs;
  }

  /**
   * Gets a list of syncs ordered by created time.
   *
   * @param string $operator
   *   Which way to order the list.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync[]
   *   The list of restores.
   */
  public function getSyncsByCreatedTime($operator = 'DESC'): array {
    return $this->sortObjectsByCreationTime($this->getSyncs(), $operator);
  }

  /**
   * Adds a sync to the list.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $sync
   *   The sync to add to the list.
   *
   * @return $this
   *   The calling class.
   */
  public function addSync(Sync $sync): SyncList {
    $this->syncs[] = $sync;
    return $this;
  }

}
