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

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $object */
    $data = [
      'apiVersion' => 'ark.heptio.com/v1',
      'kind' => 'Backup',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' =>  $object->getName(),
        'namespace' => 'heptio-ark',
      ],
      'spec' => [
        // @todo implement hooks.
        'hooks'=> [
          'resources'=> NULL,
        ],
        'labelSelector'=> [
          'matchLabels'=> $object->getMatchLabels(),
        ],
        'storageLocation'=> 'default',
        'ttl'=>  $object->getTtl(),
        'volumeSnapshotLocations'=> null
      ],
    ];

    return $data;
  }

}
