<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;

/**
 * Serializer for RestoreList objects.
 */
class RestoreListNormalizer extends BaseNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = RestoreList::class;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $restores = RestoreList::create();

    foreach ($data['items'] as $restoreData) {
      $restores->addRestore($this->serializer->denormalize($restoreData, Restore::class));
    }

    return $restores;
  }

}
