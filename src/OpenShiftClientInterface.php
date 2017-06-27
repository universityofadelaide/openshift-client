<?php

namespace UniversityOfAdelaide\OpenShift;

/**
 * Interface OpenShiftClientInterface.
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
  public function getSecret(string $name);

  /**
   * Creates a new secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be stored.
   * @param array $data
   *   Array of key => values to be stored. These will base64 encoded.
   *
   * @return bool|mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createSecret(string $name, array $data);

  /**
   * Updates an existing secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be updated.
   * @param array $data
   *   Array of key => values to be stored. These will be base64 encoded.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateSecret(string $name, array $data);

  /**
   * Delete an existing secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be updated.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteSecret(string $name);

  /**
   * Retrieves a service that matches the name.
   *
   * @param string $name
   *   Name of the service to retrieved.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getService(string $name);

  /**
   * Creates a new service based on the name and config data given.
   *
   * @param string $name
   *   Name of service.
   * @param array $data
   *   Configuration data for service.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createService(string $name, array $data);

  /**
   * Update and existing service.
   *
   * @param string $name
   *   Name of service.
   * @param array $data
   *   Configuration data for service.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateService(string $name, array $data);

  /**
   * Deletes a named service.
   *
   * @param string $name
   *   Name of service to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteService(string $name);

  /**
   * Gets all routes for the current working namespace.
   *
   * @param string $name
   *   The name of the route to retrieve.
   *
   * @return mixed
   *   Details of the route.
   */
  public function getRoute(string $name);

  /**
   * Creates a route.
   *
   * @param string $name
   *   Name for the route.
   * @param string $service_name
   *   The service name to associate with the route.
   * @param string $application_domain
   *   The application domain to associate with the route.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createRoute(string $name, string $service_name, string $application_domain);

  /**
   * Updates an existing named route.
   *
   * @param string $name
   *   Name of the route.
   * @param string $service_name
   *   The service name to associate with the route.
   * @param string $application_domain
   *   The application domain to associate with the route.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateRoute(string $name, string $service_name, string $application_domain);

  /**
   * Deletes a named routes.
   *
   * @param string $name
   *   Name of the route.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteRoute(string $name);

  /**
   * Retrieves all build configs binded to current working namespace.
   *
   * @param string $name
   *   Name of build config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getBuildConfig(string $name);

  /**
   * Create build config.
   *
   * @param string $name
   *   Name of build config.
   * @param string $secret
   *   Name of existing secret to use.
   * @param string $image_stream_tag
   *   Name of imagestream.
   * @param array $data
   *   Build config data.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createBuildConfig(string $name, string $secret, string $image_stream_tag, array $data);

  /**
   * Updates an existing build config by name.
   *
   * @param string $name
   *   Name of build config.
   * @param string $secret
   *   Name of existing secret to use.
   * @param string $image_stream_tag
   *   Name of imagestream.
   * @param array $data
   *   Build config data.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateBuildConfig(string $name, string $secret, string $image_stream_tag, array $data);

  /**
   * Deletes a build config by name.
   *
   * @param string $name
   *   Name of build config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteBuildConfig(string $name);

  /**
   * Retrieves all image streams under current namespace.
   *
   * @param string $name
   *   Name of image stream.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getImageStream(string $name);

  /**
   * Creates an image stream, needed for buildConfig.
   *
   * @param string $name
   *   Name of imagestream to create.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createImageStream(string $name);

  /**
   * Updates an image stream.
   *
   * @param string $name
   *   Name of imagestream to update.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateImageStream(string $name);

  /**
   * Deletes an image stream.
   *
   * @param string $name
   *   Name of imagestream to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteImageStream(string $name);

  /**
   * Retrieves the specified ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getImageStreamTag(string $name);

  /**
   * Create an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createImageSteamTag(string $name);

  /**
   * Update an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateImageSteamTag(string $name);

  /**
   * Delete an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteImageSteamTag(string $name);

  /**
   * Retrieve a persistent volume claim.
   *
   * @param string $name
   *   Name of the service to retrieved.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getPersistentVolumeClaim(string $name);

  /**
   * Create a persistent volume claim.
   *
   * @param string $name
   *   Name of the PersistentVolumeClaim.
   * @param string $access_mode
   *   Access mode of the PersistentVolumeClaim.
   * @param string $storage
   *   Amount of storage to allocated to the PersistentVolumeClaim.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createPersistentVolumeClaim(string $name, string $access_mode, string $storage);

  /**
   * Update a persistent volume claim.
   *
   * @param string $name
   *   Name of the PersistentVolumeClaim.
   * @param string $access_mode
   *   Access mode of the PersistentVolumeClaim.
   * @param string $storage
   *   Amount of storage to allocated to the PersistentVolumeClaim.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updatePersistentVolumeClaim(string $name, string $access_mode, string $storage);

  /**
   * Delete a persistent volume claim.
   *
   * @param string $name
   *   Name of persistent volume claim to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deletePersistentVolumeClaim(string $name);

  /**
   * Retrieve a deployment configs.
   *
   * @param string $label
   *   Label name of deployment configs to retrieve.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getDeploymentConfig(string $label);

  /**
   * Creates a deployment config.
   *
   * @param string $name
   *   Name of the deploymentconfig.
   * @param string $image_stream_tag
   *   Image stream to manage the deployment.
   * @param string $image_name
   *   Image name for deployment.
   * @param array $volumes
   *   Volumes to attach to the deployment config.
   * @param array $data
   *   Configuration data for deployment config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createDeploymentConfig(string $name, string $image_stream_tag, string $image_name, array $volumes, array $data);

  /**
   * Updates and existing deployment config.
   *
   * @param string $name
   *   Name of the deploymentconfig.
   * @param string $image_stream_tag
   *   Image stream to manage the deployment.
   * @param string $image_name
   *   Image name for deployment.
   * @param array $volumes
   *   Volumes to attach to the deployment config.
   * @param array $data
   *   Configuration data for deployment config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateDeploymentConfig(string $name, string $image_stream_tag, string $image_name, array $volumes, array $data);

  /**
   * Deletes a deployment config by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteDeploymentConfig(string $name);

  /**
   * Retrieve multiple deployment configs.
   *
   * @param string $label
   *   Label name of deployment configs to retrieve.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getDeploymentConfigs(string $label);

  /**
   * Retrieve a cron job.
   *
   * @param string $name
   *   Name of cron job.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getCronJob(string $name);

  /**
   * Creates a cron job.
   *
   * @param string $name
   *   Name of cron job.
   *   Image stream to manage the deployment.
   * @param string $image_name
   *   Image name for deployment.
   * @param string $schedule
   *   The cron styl schedule to run the job.
   * @param array $args
   *   The commands to run, each entry in the array is a command.
   * @param array $volumes
   *   Volumes to attach to the deployment config.
   * @param array $data
   *   Configuration data for deployment config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 201.
   */
  public function createCronJob(string $name, string $image_name, string $schedule, array $args, array $volumes, array $data);

  /**
   * Updates an existing cron job.
   *
   * @param string $name
   *   Name of cron job..
   * @param string $image_name
   *   Image name for deployment.
   * @param array $volumes
   *   Volumes to attach to the deployment config.
   * @param array $data
   *   Configuration data for deployment config.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function updateCronJob(string $name, string $image_name, array $volumes, array $data);

  /**
   * Deletes a cron job by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteCronJob(string $name);

  /**
   * Retrieve a pod.
   *
   * @param string $name
   *   Name of pod.
   * @param string $label
   *   Label of items to retrieve.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getPod($name, $label);

  /**
   * Deletes a pod by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deletePod(string $name);

  /**
   * Retrieve a replication controller..
   *
   * @param string $name
   *   Name of pod.
   * @param string $label
   *   Label of items to retrieve.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails to get back a 200.
   */
  public function getReplicationControllers($name, $label);

  /**
   * Deletes a replicaion controller by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   * @param string $label
   *   Label of items to delete.
   *
   * @return mixed
   *   Returns the body response if successful
   *   otherwise false if request fails.
   */
  public function deleteReplicationControllers($name, $label);

}
