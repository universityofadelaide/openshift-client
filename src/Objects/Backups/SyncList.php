<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a SyncList.
 */
class SyncList extends ObjectListBase {

  /**
   * The list of syncs.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync[]
   */
  protected $syncs = [];

  /**
   * Factory method for creating a new SyncList.
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
   *   The list of sorted syncs.
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

  /**
   * Gets the number of syncs.
   *
   * @return int
   *   The number of backups in this list.
   */
  public function getSyncCount(): int {
    return count($this->getSyncs());
  }

  /**
   * Checks there are syncs in this list.
   *
   * @return bool
   *   TRUE if there are any syncs.
   */
  public function hasSyncs():bool {
    return (bool) $this->getSyncCount();
  }

}
