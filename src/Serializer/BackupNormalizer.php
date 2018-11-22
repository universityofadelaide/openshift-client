<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;

/**
 * Serializer for Result objects.
 */
class BackupNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = Backup::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $backup = Backup::create();
    $backup->setName($data['metadata']['name'])
    ->setLabels($data['metadata']['labels'])
    // @todo implement hooks.
    ->setHooks([])
    ->setTtl($data['spec']['ttl'])
    ->setMatchLabels($data['spec']['labelSelector']['matchLabels'])
    ->setPhase($data['status']['phase'])
    ->setStartTimestamp($data['status']['startTimestamp'])
    ->setCompletionTimestamp($data['status']['completionTimestamp'])
    ->setExpires($data['status']['expiration']);
    return $backup;
  }

}
