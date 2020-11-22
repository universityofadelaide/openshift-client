<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for Horizontal Pod Autoscalers.
 */
class Hpa extends ObjectBase {

  /**
   * The min replicas for the HPA.
   *
   * @var int
   */
  protected $minReplicas = 1;

  /**
   * The max replicas for the HPA.
   *
   * @var int
   */
  protected $maxReplicas = 2;

  /**
   * The target CPU for the HPA.
   *
   * @var int
   */
  protected $targetCpu = 80;

  /**
   * Gets the value of MinReplicas.
   *
   * @return int
   *   Value of MinReplicas.
   */
  public function getMinReplicas(): int {
    return $this->minReplicas;
  }

  /**
   * Sets the value of MinReplicas.
   *
   * @param int $minReplicas
   *   The value for MinReplicas.
   *
   * @return Hpa
   *   The calling class.
   */
  public function setMinReplicas(int $minReplicas): Hpa {
    $this->minReplicas = $minReplicas;
    return $this;
  }

  /**
   * Gets the value of MaxReplicas.
   *
   * @return int
   *   Value of MaxReplicas.
   */
  public function getMaxReplicas(): int {
    return $this->maxReplicas;
  }

  /**
   * Sets the value of MaxReplicas.
   *
   * @param int $maxReplicas
   *   The value for MaxReplicas.
   *
   * @return Hpa
   *   The calling class.
   */
  public function setMaxReplicas(int $maxReplicas): Hpa {
    $this->maxReplicas = $maxReplicas;
    return $this;
  }

  /**
   * Gets the value of TargetCpu.
   *
   * @return int
   *   Value of TargetCpu.
   */
  public function getTargetCpu(): int {
    return $this->targetCpu;
  }

  /**
   * Sets the value of TargetCpu.
   *
   * @param int $targetCpu
   *   The value for TargetCpu.
   *
   * @return Hpa
   *   The calling class.
   */
  public function setTargetCpu(int $targetCpu): Hpa {
    $this->targetCpu = $targetCpu;
    return $this;
  }

}
