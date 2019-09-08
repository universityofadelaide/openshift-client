<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * A base class for objects.
 */
abstract class ObjectBase {

  /**
   * The name of the object.
   *
   * @var string
   */
  protected $name;

  /**
   * An array of labels.
   *
   * @var array
   */
  protected $labels = [];

  /**
   * The time the object was created.
   *
   * @var string
   */
  protected $creationTimestamp;

  /**
   * Factory method for creating a new object.
   *
   * @return self
   *   Returns static object.
   */
  public static function create() {
    return new static();
  }

  /**
   * Gets the value of name.
   *
   * @return string
   *   Value of name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Sets the name.
   *
   * @param string $name
   *   The name of the object.
   *
   * @return $this
   *   The calling class.
   */
  public function setName(string $name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Gets the value of labels.
   *
   * @return array
   *   Value of labels.
   */
  public function getLabels(): array {
    return $this->labels;
  }

  /**
   * Sets the array of labels.
   *
   * @param array $labels
   *   An array of labels.
   *
   * @return $this
   *   The calling class.
   */
  public function setLabels(array $labels) {
    $this->labels = $labels;
    return $this;
  }

  /**
   * Set a single label.
   *
   * @param \UniversityOfAdelaide\OpenShift\Objects\Label $label
   *   The label object.
   *
   * @return $this
   *   The calling class.
   */
  public function setLabel(Label $label) {
    $this->labels[$label->getKey()] = $label->getValue();
    return $this;
  }

  /**
   * Get a single label.
   *
   * @param string $key
   *   The key of the label.
   *
   * @return string|bool
   *   The label value, or FALSE if it doesn't exist.
   */
  public function getLabel(string $key): string {
    return isset($this->getLabels()[$key]) ? $this->getLabels()[$key] : FALSE;
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
   * @return $this
   *   The calling class.
   */
  public function setCreationTimestamp(string $creationTimestamp) {
    $this->creationTimestamp = $creationTimestamp;
    return $this;
  }

}
