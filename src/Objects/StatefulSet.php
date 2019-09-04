<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for StatefulSets.
 */
class StatefulSet extends ObjectBase {

  /**
   * The stateful set spec.
   *
   * @var array
   */
  protected $spec = [];

  /**
   * Gets the value of Spec.
   *
   * @return array
   *   Value of Spec.
   */
  public function getSpec(): array {
    return $this->spec;
  }

  /**
   * Sets the value of Spec.
   *
   * @param array $spec
   *   The value for Spec.
   *
   * @return StatefulSet
   *   The calling class.
   */
  public function setSpec(array $spec): StatefulSet {
    $this->spec = $spec;
    return $this;
  }

}
