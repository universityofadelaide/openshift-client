<?php

namespace UniversityOfAdelaide\OpenShift;

/**
 * Interface OpenShiftClientInterface.
 *
 * @package UnviersityofAdelaide\OpenShift.
 */
interface ClientInterface {

  /**
   * Retrieves a secret that matches the name/tag.
   *
   * @param string $name
   *   Name of secret to be retrieved.
   *
   * @return array|bool
   *   Returns the secret, base64 decoded, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving secret.
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
   * @return array
   *   Returns the secret if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating secret.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating secret.
   */
  public function updateSecret(string $name, array $data);

  /**
   * Delete an existing secret using the name provided.
   *
   * @param string $name
   *   Name of the secret to be updated.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting secret.
   */
  public function deleteSecret(string $name);

  /**
   * Retrieves a service that matches the name.
   *
   * @param string $name
   *   Name of the service to retrieved.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving service.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating service.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating service.
   */
  public function updateService(string $name, array $data);

  /**
   * Deletes a named service.
   *
   * @param string $name
   *   Name of service to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting service.
   */
  public function deleteService(string $name);

  /**
   * Gets all routes for the current working namespace.
   *
   * @param string $name
   *   The name of the route to retrieve.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving route.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating route.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating route.
   */
  public function updateRoute(string $name, string $service_name, string $application_domain);

  /**
   * Deletes a named routes.
   *
   * @param string $name
   *   Name of the route.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting route.
   */
  public function deleteRoute(string $name);

  /**
   * Retrieves all build configs binded to current working namespace.
   *
   * @param string $name
   *   Name of build config.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving build config.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating build config.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating build config.
   */
  public function updateBuildConfig(string $name, string $secret, string $image_stream_tag, array $data);

  /**
   * Deletes a build config by name.
   *
   * @param string $name
   *   Name of build config.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting build config.
   */
  public function deleteBuildConfig(string $name);

  /**
   * Retrieves all image streams under current namespace.
   *
   * @param string $name
   *   Name of image stream.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving image stream.
   */
  public function getImageStream(string $name);

  /**
   * Creates an image stream, needed for buildConfig.
   *
   * @param string $name
   *   Name of imagestream to create.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating image stream.
   */
  public function createImageStream(string $name);

  /**
   * Updates an image stream.
   *
   * @param string $name
   *   Name of imagestream to update.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating image stream.
   */
  public function updateImageStream(string $name);

  /**
   * Deletes an image stream.
   *
   * @param string $name
   *   Name of imagestream to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting image stream.
   */
  public function deleteImageStream(string $name);

  /**
   * Retrieves the specified ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving image stream tag.
   */
  public function getImageStreamTag(string $name);

  /**
   * Create an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating image stream tag.
   */
  public function createImageSteamTag(string $name);

  /**
   * Update an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating image stream tag.
   */
  public function updateImageSteamTag(string $name);

  /**
   * Delete an ImageStreamTag.
   *
   * @param string $name
   *   Name of the image stream tag.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting image stream tag.
   */
  public function deleteImageSteamTag(string $name);

  /**
   * Retrieve a persistent volume claim.
   *
   * @param string $name
   *   Name of the service to retrieved.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving persistent volume claim.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating persistent volume claim.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating persistent volume claim.
   */
  public function updatePersistentVolumeClaim(string $name, string $access_mode, string $storage);

  /**
   * Delete a persistent volume claim.
   *
   * @param string $name
   *   Name of persistent volume claim to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting persistent volume claim.
   */
  public function deletePersistentVolumeClaim(string $name);

  /**
   * Retrieve a deployment configs.
   *
   * @param string $label
   *   Label name of deployment configs to retrieve.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving deployment config.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating deployment config.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating deployment config.
   */
  public function updateDeploymentConfig(string $name, string $image_stream_tag, string $image_name, array $volumes, array $data);

  /**
   * Deletes a deployment config by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting deployment config.
   */
  public function deleteDeploymentConfig(string $name);

  /**
   * Retrieve multiple deployment configs by label.
   *
   * @param string $label
   *   Label name of deployment configs to retrieve.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving deployment configs.
   */
  public function getDeploymentConfigs(string $label);

  /**
   * Retrieve a cron job.
   *
   * @param string $name
   *   Name of cron job.
   *
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving cron job.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue creating cron job.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue updating cron job.
   */
  public function updateCronJob(string $name, string $image_name, array $volumes, array $data);

  /**
   * Deletes a cron job by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting cron job.
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
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving pod.
   */
  public function getPod($name, $label);

  /**
   * Deletes a pod by name.
   *
   * @param string $name
   *   Name of deployment config to delete.
   *
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting pod.
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
   * @return array|bool
   *   Returns the body response if successful, false if it does not exist.
   *
   * @throws ClientException
   *   Throws exception if there is an issue retrieving replication controllers.
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
   * @return array
   *   Returns the body response if successful.
   *
   * @throws ClientException
   *   Throws exception if there is an issue deleting replication controllers.
   */
  public function deleteReplicationControllers($name, $label);

}
