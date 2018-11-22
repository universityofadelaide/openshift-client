<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object for a backup Hook.
 */
class Hook {

  /**
   * Factory method for creating a new Hook.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
  }

}
