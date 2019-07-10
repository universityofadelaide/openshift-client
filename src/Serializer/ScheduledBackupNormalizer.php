<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;

/**
 * Serializer for ScheduledBackup objects.
 */
class ScheduledBackupNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = ScheduledBackup::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $schedule = ScheduledBackup::create();
    $schedule->setName($data['metadata']['name'])
      ->setTtl($data['spec']['template']['ttl'])
      ->setMatchLabels($data['spec']['template']['labelSelector']['matchLabels'])
      ->setSchedule($data['spec']['schedule']);
    if (isset($data['metadata']['labels'])) {
      $schedule->setLabels($data['metadata']['labels']);
    }
    if (isset($data['status']['phase'])) {
      $schedule->setPhase($data['status']['phase']);
    }
    if (isset($data['status']['lastBackup'])) {
      $schedule->setLastBackup($data['status']['lastBackup']);
    }
    return $schedule;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup $object */
    $data = [
      'apiVersion' => 'ark.heptio.com/v1',
      'kind' => 'Schedule',
      'metadata' => [
        'labels' => $object->getLabels(),
        'name' => $object->getName(),
        'namespace' => 'heptio-ark',
      ],
      'spec' => [
        'schedule' => $object->getSchedule(),
        'template' => [
          'labelSelector' => [
            'matchLabels' => $object->getMatchLabels(),
          ],
          'ttl' => $object->getTtl(),
        ],
      ],
    ];

    return $data;
  }

}
