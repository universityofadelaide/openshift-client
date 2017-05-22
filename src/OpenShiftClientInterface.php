<?php

namespace UniversityOfAdelaide\OpenShift;

/**
 * Interface OpenShiftClientInterface
 *
 * @package UnviersityofAdelaide\OpenShift.
 */
interface OpenShiftClientInterface {
  /**
   * Retrieves a secret that matches the name/tag.
   *
   * @param string $name
   *   Name of secret to be retrieved.
   *
   * @return mixed
   *   Returns the secret, base64 decoded.
   */
  public function getSecret($name);

  /**
   * Creates a new secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be stored.
   * @param array $data
   *   Array of key => values to be stored. These will base64 encoded.
   *
   * @return bool|mixed
   *   Returns the body response if successful otherwise false if request fails to get back a 201.
   */
  public function createSecret($name, array $data);

  /**
   * Updates an existing secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be updated.
   * @param array $data
   *   Array of key => values to be stored. These will be base64 encoded.
   *
   * @return mixed
   *   Returns the body response if successful otherwise false if request fails.
   */
  public function updateSecret($name, array $data);

  /**
   * Retrieves a service that matches the name.
   *
   * @param string $name
   *   Name of the service to retrieved.
   *
   * @return mixed
   *   Returns the body response if successful otherwise false if request fails to get back a 200.
   */
  public function getService($name);

  /**
   * Creates a new service based on the name and config data given.
   *
   * @param string $name
   *    Name of service.
   * @param array $data
   *    Configuration data for service.
   *
   * @return mixed
   *   Returns the body response if successful otherwise false if request fails to get back a 201.
   */
  public function createService($name, array $data);

  /**
   * @return mixed
   */
  public function updateService();

  /**
   * Deletes a named service.
   *
   * @param string $name Name of service
   * @return mixed
   *   Returns the body response if successful otherwise false if request fails.
   */
  public function deleteService($name);

  public function getRoute();

  public function createRoute();

  public function updateRoute();

  public function deleteRoute();

  public function getBuildConfig();

  public function createBuildConfig();

  public function updateBuildConfig();

  public function deleteBuildConfig();

  public function getImageStream();

  /**
   * Creates an image stream, needed for buildConfig.
   *
   * @param string $name Name of imagestream, '-imagestream' appended to string.
   * @return mixed
   */
  public function createImageStream($name);

  public function updateImageStream();

  public function deleteImageStream();

  public function getImageStreamTag();

  public function createImageSteamTag();

  public function updateImageSteamTag();

  public function deleteImageSteamTag();

  public function getPersistentVolumeClaim();

  public function createPersistentVolumeClaim();

  public function updatePersistentVolumeClaim();

  public function deletePersistentVolumeClaim();

  public function getDeploymentConfig();

  public function createDeploymentConfig();

  public function updateDeploymentConfig();

  public function deleteDeploymentConfig();


}