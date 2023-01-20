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
  protected array $ingressPodMatchLabels = [];

  /**
   * The config map data.
   *
   * @var array
   */
  protected array $ingressNamespaceMatchLabels = [];

  /**
   * The config map data.
   *
   * @var array
   */
  protected array $podSelectorMatchLabels = [];

  /**
   * The port to allow.
   *
   * @var int
   */
  protected int $port;

  /**
   * Gets the value of IngressPodMatchLabels.
   *
   * @return array
   *   Value of IngressPodMatchLabels.
   */
  public function getIngressPodMatchLabels(): array {
    return $this->ingressPodMatchLabels;
  }

  /**
   * Sets the value of IngressPodMatchLabels.
   *
   * @param array $ingressPodMatchLabels
   *   The value for IngressPodMatchLabels.
   *
   * @return NetworkPolicy
   *   The calling class.
   */
  public function setIngressPodMatchLabels(array $ingressPodMatchLabels): NetworkPolicy {
    $this->ingressPodMatchLabels = $ingressPodMatchLabels;
    return $this;
  }

  /**
   * Gets the value of IngressNamespaceMatchLabels.
   *
   * @return array
   *   Value of IngressNamespaceMatchLabels.
   */
  public function getIngressNamespaceMatchLabels(): array {
    return $this->ingressNamespaceMatchLabels;
  }

  /**
   * Sets the value of IngressMatchLabels.
   *
   * @param array $ingressNamespaceMatchLabels
   *   The value for IngressNamespaceMatchLabels.
   *
   * @return NetworkPolicy
   *   The calling class.
   */
  public function setIngressNamespaceMatchLabels(array $ingressNamespaceMatchLabels): NetworkPolicy {
    $this->ingressNamespaceMatchLabels = $ingressNamespaceMatchLabels;
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
