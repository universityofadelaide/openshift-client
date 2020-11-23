<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for Network Policies.
 */
class NetworkPolicy extends ObjectBase {

  /**
   * The config map data.
   *
   * @var array
   */
  protected $ingressMatchLabels = [];

  /**
   * The config map data.
   *
   * @var array
   */
  protected $podSelectorMatchLabels = [];

  /**
   * The port to allow.
   *
   * @var int
   */
  protected $port;

  /**
   * Gets the value of IngressMatchLabels.
   *
   * @return array
   *   Value of IngressMatchLabels.
   */
  public function getIngressMatchLabels(): array {
    return $this->ingressMatchLabels;
  }

  /**
   * Sets the value of IngressMatchLabels.
   *
   * @param array $ingressMatchLabels
   *   The value for IngressMatchLabels.
   *
   * @return NetworkPolicy
   *   The calling class.
   */
  public function setIngressMatchLabels(array $ingressMatchLabels): NetworkPolicy {
    $this->ingressMatchLabels = $ingressMatchLabels;
    return $this;
  }

  /**
   * Gets the value of PodSelectorMatchLabels.
   *
   * @return array
   *   Value of PodSelectorMatchLabels.
   */
  public function getPodSelectorMatchLabels(): array {
    return $this->podSelectorMatchLabels;
  }

  /**
   * Sets the value of PodSelectorMatchLabels.
   *
   * @param array $podSelectorMatchLabels
   *   The value for PodSelectorMatchLabels.
   *
   * @return NetworkPolicy
   *   The calling class.
   */
  public function setPodSelectorMatchLabels(array $podSelectorMatchLabels): NetworkPolicy {
    $this->podSelectorMatchLabels = $podSelectorMatchLabels;
    return $this;
  }

  /**
   * Gets the value of Port.
   *
   * @return int
   *   Value of Port.
   */
  public function getPort(): int {
    return $this->port;
  }

  /**
   * Sets the value of Port.
   *
   * @param int $port
   *   The value for Port.
   *
   * @return NetworkPolicy
   *   The calling class.
   */
  public function setPort(int $port): NetworkPolicy {
    $this->port = $port;
    return $this;
  }

}
