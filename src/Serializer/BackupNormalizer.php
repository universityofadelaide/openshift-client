<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $backup */
    $backup = Backup::create();
    $backup->setName($data['metadata']['name'])
      ->setLabels($data['metadata']['labels'])
      ->setCreationTimestamp($data['metadata']['creationTimestamp']);
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
    if (isset($data['status']['resticId'])) {
      $backup->setResticId($data['status']['resticId']);
    }
    return $backup;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    $volumes = [];
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $object */
    foreach ($object->getVolumes() as $volumeId => $claimName) {
      $volumes[$volumeId] = ['claimName' => $claimName];
    }
    $data = [
      'apiVersion' => 'extensions.shepherd.io/v1beta1',
      'kind' => 'Backup',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'volumes' => $volumes,
        'mysql' => array_reduce($object->getDatabases(), function ($carry, Database $db) {
          $carry[$db->getId()] = [
            'secret' => [
              'name' => $db->getSecretName(),
              'keys' => $db->getSecretKeys(),
            ],
          ];
          return $carry;
        }, []),
      ],
    ];
    if ($object->hasAnnotations()) {
      $data['metadata']['annotations'] = $object->getAnnotations();
    }

    return $data;
  }

}
