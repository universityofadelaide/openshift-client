<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

/**
 * Serializer for Backup objects.
 */
class BackupNormalizer extends BaseNormalizer {

  use BackupRestoreNormalizerTrait;

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
    if (isset($data['status']['startTime'])) {
      $backup->setstartTimeStamp($data['status']['startTime']);
    }
    if (isset($data['status']['completionTime'])) {
      $backup->setcompletionTimeStamp($data['status']['completionTime']);
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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $object */
    $data = [
      'apiVersion' => 'extension.shepherd/v1',
      'kind' => 'Backup',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
      ],
      'spec' => [
        'volumes' => $this->normalizeVolumes($object),
        'mysql' => $this->normalizeMysqls($object),
      ],
    ];
    if ($object->hasAnnotations()) {
      $data['metadata']['annotations'] = $object->getAnnotations();
    }

    return $data;
  }

}
