<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Backups\SyncList;

/**
 * Serializer for SyncList objects.
 */
class SyncListNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = SyncList::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $list = SyncList::create();

    foreach ($data['items'] as $syncData) {
      $list->addSync($this->serializer->denormalize($syncData, Sync::class));
    }

    return $list;
  }

}
