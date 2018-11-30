<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines a value object representing a Sync environment.
 */
class SyncEnvironment extends ObjectBase {

  /**
   * The name of the pvc to sync for this environment.
   *
   * @var string
   */
  protected $persistentVolumeClaim;

  /**
   * The name of the secret to get credentials for this sync environment.
   *
   * @var string
   */
  protected $secret;

  /**
   * Create a SyncEnvironment from a pvc and secret string.
   *
   * @param string $pvc
   *   The pvc name.
   * @param string $secret
   *   The secret name.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   *   The resulting object.
   */
  public static function createFromPvcAndSecret(string $pvc, string $secret) {
    return (new static())->setPersistentVolumeClaim($pvc)->setSecret($secret);
  }

  /**
   * Gets the value of PersistentVolumeClaim.
   *
   * @return string
   *   Value of PersistentVolumeClaim.
   */
  public function getPersistentVolumeClaim(): string {
    return $this->persistentVolumeClaim;
  }

  /**
   * Sets the value of PersistentVolumeClaim.
   *
   * @param string $persistentVolumeClaim
   *   The value for PersistentVolumeClaim.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   *   The calling class.
   */
  public function setPersistentVolumeClaim(string $persistentVolumeClaim): SyncEnvironment {
    $this->persistentVolumeClaim = $persistentVolumeClaim;
    return $this;
  }

  /**
   * Gets the value of Secret.
   *
   * @return string
   *   Value of Secret.
   */
  public function getSecret(): string {
    return $this->secret;
  }

  /**
   * Sets the value of Secret.
   *
   * @param string $secret
   *   The value for Secret.
   *
   * @return \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment
   *   The calling class.
   */
  public function setSecret(string $secret): SyncEnvironment {
    $this->secret = $secret;
    return $this;
  }

}
