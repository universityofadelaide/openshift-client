<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;

/**
 * Serializer for Backup objects.
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
      ->setTtl($data['spec']['ttl'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp'])
      ->setMatchLabels($data['spec']['labelSelector']['matchLabels']);
    if (isset($data['metadata']['annotations'])) {
      $backup->setAnnotations($data['metadata']['annotations']);
    }
    if (isset($data['status']['phase'])) {
      $backup->setPhase($data['status']['phase']);
    }
    if (isset($data['status']['startTimestamp'])) {
      $backup->setStartTimestamp($data['status']['startTimestamp']);
    }
    if (isset($data['status']['completionTimestamp'])) {
      $backup->setCompletionTimestamp($data['status']['completionTimestamp']);
    }
    if (isset($data['status']['expiration'])) {
      $backup->setExpires($data['status']['expiration']);
    }
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
        'name' => $object->getName(),
        'namespace' => 'heptio-ark',
      ],
      'spec' => [
        'labelSelector' => [
          'matchLabels' => $object->getMatchLabels(),
        ],
        'storageLocation' => 'default',
        'ttl' => $object->getTtl(),
        'volumeSnapshotLocations' => NULL
      ],
    ];
    if ($object->hasAnnotations()) {
      $data['metadata']['annotations'] = $object->getAnnotations();
    }

    return $data;
  }

}
