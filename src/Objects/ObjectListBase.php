<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Base class for list objects.
 */
abstract class ObjectListBase {

  /**
   * Sorts an array of objects by created time.
   *
   * @param array $objects
   *   The array of objects.
   * @param string $operator
   *   The sort operator.
   *
   * @return array
   *   The sorted array.
   */
  protected function sortObjectsByCreationTime(array $objects, string $operator) {
    usort($objects, function (ObjectBase $a, ObjectBase $b) use ($operator) {
      return $operator === 'DESC' ? $a->getCreationTimestamp() < $b->getCreationTimestamp() : $a->getCreationTimestamp() > $b->getCreationTimestamp();
    });
    return $objects;
  }

}
