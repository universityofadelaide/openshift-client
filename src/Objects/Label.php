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

  public static function create(string $key, string $value) {
    $instance = new static();
    $instance->setKey($key)->setValue($value);
    return $instance;
  }

  /**
   * Gets the value of {Key}
   *
   * @return string
   *  Value of {Key}
   */
  public function getKey(): string {
    return $this->key;
  }

  /**
   * Sets the value of Key
   *
   * @param string $key
   *  The value for {Key}
   *
   * @return Label
   *  The calling class.
   */
  public function setKey(string $key): Label {
    $this->key = $key;
    return $this;
  }

  /**
   * Gets the value of {Value}
   *
   * @return string
   *  Value of {Value}
   */
  public function getValue(): string {
    return $this->value;
  }

  /**
   * Sets the value of Value
   *
   * @param string $value
   *  The value for {Value}
   *
   * @return Label
   *  The calling class.
   */
  public function setValue(string $value): Label {
    $this->value = $value;
    return $this;
  }

  /**
   * Turn the label into a string.
   *
   * @return string
   */
  public function __toString() {
    return sprintf('%s=%s', $this->getKey(), $this->getValue());
  }

}
