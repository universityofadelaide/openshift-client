<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * Value object for config maps.
 */
class ConfigMap extends ObjectBase {

  /**
   * The config map data.
   *
   * @var array
   */
  protected $data = [];

  /**
   * Gets the value of Data.
   *
   * @return array
   *   Value of Data.
   */
  public function getData(): array {
    return $this->data;
  }

  /**
   * Sets the value of Data.
   *
   * @param array $data
   *   The value for Data.
   *
   * @return ConfigMap
   *   The calling class.
   */
  public function setData(array $data): ConfigMap {
    $this->data = $data;
    return $this;
  }

}
