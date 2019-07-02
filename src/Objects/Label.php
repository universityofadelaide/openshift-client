<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for a OS label.
 */
class Label {

  /**
   * The label key.
   *
   * @var string
   */
  protected $key;

  /**
   * The label value.
   *
   * @var string
   */
  protected $value;

  /**
   * Create a label from a key and value.
   *
   * @param string $key
   *   The key.
   * @param string $value
   *   The label.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Label
   *   The object.
   */
  public static function create(string $key, string $value) {
    $instance = new static();
    $instance->setKey($key)->setValue($value);
    return $instance;
  }

  /**
   * Gets the value of key.
   *
   * @return string
   *   Value of key.
   */
  public function getKey(): string {
    return $this->key;
  }

  /**
   * Sets the value of key.
   *
   * @param string $key
   *   The value for key.
   *
   * @return Label
   *   The calling class.
   */
  public function setKey(string $key): Label {
    $this->key = $key;
    return $this;
  }

  /**
   * Gets the value of value.
   *
   * @return string
   *   Value of value.
   */
  public function getValue(): string {
    return $this->value;
  }

  /**
   * Sets the value of value.
   *
   * @param string $value
   *   The value for value.
   *
   * @return Label
   *   The calling class.
   */
  public function setValue(string $value): Label {
    $this->value = $value;
    return $this;
  }

  /**
   * Turn the label into a string.
   *
   * @return string
   *   The string representation of the label.
   */
  public function __toString() {
    return sprintf('%s=%s', $this->getKey(), $this->getValue());
  }

}
