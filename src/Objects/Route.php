<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for Routes.
 */
class Route extends ObjectBase {

  /**
   * Annotations
   *
   * E.g.
   * ['some-name' => 'some-value']
   *
   * @var array
   */
  protected $annotations = [];

  /**
   * Host.
   *
   * @var string
   */
  protected $host;

  /**
   * Path.
   *
   * @var string
   */
  protected $path;

  /**
   * Insecure edge termination policy.
   *
   * "None", "Allow" or "Redirect".
   *
   * @var string
   */
  protected $insecureEdgeTerminationPolicy;

  /**
   * Termination.
   *
   * "edge" or "passthrough".
   *
   * @var string
   */
  protected $termination;

  /**
   * To kind.
   *
   * @var string
   */
  protected $toKind;

  /**
   * To name.
   *
   * @var string
   */
  protected $toName;

  /**
   * To weight.
   *
   * @var int
   */
  protected $toWeight = 100;

  /**
   * Wildcare policy.
   *
   * @var string
   */
  protected $wildcardPolicy;

  /**
   * Gets the value of Annotations.
   *
   * @return array
   */
  public function getAnnotations(): array {
    return $this->annotations;
  }

  /**
   * Sets the value of Annotations.
   *
   * @param array $annotations
   *
   * @return Route
   *   The calling class.
   */
  public function setAnnotations(array $annotations): Route {
    $this->annotations = $annotations;
    return $this;
  }

  /**
   * Sets a single Annotation.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Annotation $annotation
   *   The Annotation object.
   *
   * @return Route
   *   The calling class.
   */
  public function setAnnotation(Annotation $annotation): Route {
    $this->annotations[$annotation->getKey()] = $annotation->getValue();
    return $this;
  }

  /**
   * Gets the value of Host.
   *
   * @return string
   */
  public function getHost(): string {
    return $this->host;
  }

  /**
   * Sets the value of Host.
   *
   * @param string $host
   *
   * @return Route
   *   The calling class.
   */
  public function setHost(string $host): Route {
    $this->host = $host;
    return $this;
  }

  /**
   * Gets the value of Path.
   *
   * @return string
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * Sets the value of Path.
   *
   * @param string $path
   *
   * @return Route
   *   The calling class.
   */
  public function setPath(string $path): Route {
    $this->path = $path;
    return $this;
  }

  /**
   * Gets the value of insecure edge termination policy.
   *
   * @return string
   */
  public function getInsecureEdgeTerminationPolicy(): string {
    return $this->insecureEdgeTerminationPolicy;
  }

  /**
   * Sets the value of insecure edge termination policy.
   *
   * @param string $insecureEdgeTerminationPolicy
   *
   * @return Route
   *   The calling class.
   */
  public function setInsecureEdgeTerminationPolicy(
    string $insecureEdgeTerminationPolicy
  ): Route {
    $this->insecureEdgeTerminationPolicy = $insecureEdgeTerminationPolicy;
    return $this;
  }

  /**
   * Gets the value of Termination.
   *
   * @return string
   */
  public function getTermination(): string {
    return $this->termination;
  }

  /**
   * Sets the value of Termination.
   *
   * @param string $termination
   *
   * @return Route
   *   The calling class.
   */
  public function setTermination(string $termination): Route {
    $this->termination = $termination;
    return $this;
  }

  /**
   * Gets the value of to kind.
   *
   * @return string
   */
  public function getToKind(): string {
    return $this->toKind;
  }

  /**
   * Sets the value of to kind.
   *
   * @param string $toKind
   *
   * @return Route
   */
  public function setToKind(string $toKind): Route {
    $this->toKind = $toKind;
    return $this;
  }

  /**
   * Gets the value of to name.
   *
   * @return string
   */
  public function getToName(): string {
    return $this->toName;
  }

  /**
   * Sets the value of to name.
   *
   * @param string $toName
   *
   * @return Route
   */
  public function setToName(string $toName): Route {
    $this->toName = $toName;
    return $this;
  }

  /**
   * Gets the value of to weight.
   *
   * @return int
   */
  public function getToWeight(): int {
    return $this->toWeight;
  }

  /**
   * Sets the value of to weight.
   *
   * @param int $toWeight
   *
   * @return Route
   */
  public function setToWeight(int $toWeight): Route {
    $this->toWeight = $toWeight;
    return $this;
  }

  /**
   * Gets the value of wildcard policy.
   *
   * @return string|NULL
   */
  public function getWildcardPolicy(): ?string {
    return $this->wildcardPolicy;
  }

  /**
   * Sets the value of wildcard policy.
   *
   * @param string $wildcardPolicy
   *
   * @return Route
   */
  public function setWildcardPolicy(string $wildcardPolicy): Route {
    $this->wildcardPolicy = $wildcardPolicy;
    return $this;
  }

}
