<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Creates a serializer for OS objects.
 */
class OpenShiftSerializerFactory {

  /**
   * Creates a Serializer object.
   *
   * @return \Symfony\Component\Serializer\Serializer
   *   Returns the Serializer object.
   */
  public static function create() {
    $encoders = [
      new JsonEncoder(),
    ];
    $normalizers = [
      new BackupNormalizer(),
      new BackupListNormalizer(),
      new ConfigMapNormalizer(),
      new NetworkPolicyNormalizer(),
      new RestoreNormalizer(),
      new RestoreListNormalizer(),
      new RouteNormalizer(),
      new ScheduledBackupNormalizer(),
      new StatefulSetNormalizer(),
      new HpaNormalizer(),
      new SyncNormalizer(),
      new SyncListNormalizer(),
    ];
    return new Serializer($normalizers, $encoders);
  }

}
