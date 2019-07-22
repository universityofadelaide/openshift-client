<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Value object for the backup databases.
 */
class Database {

  /**
   * The db identifier.
   *
   * @var string
   */
  protected $id;

  /**
   * The secret name that holds the db credentials.
   *
   * @var string
   */
  protected $secretName;

  /**
   * An array of key value pairs to get the db credentials from.
   *
   * @var array
   */
  protected $secretKeys;

  /**
   * Create a db object from values.
   *
   * @param string $id
   *   The id.
   * @param string $secretName
   *   The secret name.
   * @param array $secretKeys
   *   The secret keys.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\Database
   *   The object.
   */
  public static function createFromValues($id, $secretName, array $secretKeys) {
    $instance = new static();
    $instance->setId($id)
      ->setSecretName($secretName)
      ->setSecretKeys($secretKeys);
    return $instance;
  }

  /**
   * Gets the value of Id.
   *
   * @return string
   *   Value of Id.
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Sets the value of Id.
   *
   * @param string $id
   *   The value for Id.
   *
   * @return Database
   *   The calling class.
   */
  public function setId(string $id): Database {
    $this->id = $id;
    return $this;
  }

  /**
   * Gets the value of SecretName.
   *
   * @return string
   *   Value of SecretName.
   */
  public function getSecretName(): string {
    return $this->secretName;
  }

  /**
   * Sets the value of SecretName.
   *
   * @param string $secretName
   *   The value for SecretName.
   *
   * @return Database
   *   The calling class.
   */
  public function setSecretName(string $secretName): Database {
    $this->secretName = $secretName;
    return $this;
  }

  /**
   * Gets the value of SecretKeys.
   *
   * @return array
   *   Value of SecretKeys.
   */
  public function getSecretKeys(): array {
    return $this->secretKeys;
  }

  /**
   * Sets the value of SecretKeys.
   *
   * @param array $secretKeys
   *   The value for SecretKeys.
   *
   * @return Database
   *   The calling class.
   */
  public function setSecretKeys(array $secretKeys): Database {
    $this->secretKeys = $secretKeys;
    return $this;
  }

}
