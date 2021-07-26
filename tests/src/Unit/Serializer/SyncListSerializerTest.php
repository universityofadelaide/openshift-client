<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\SyncList;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\ScheduledBackupNormalizer
 */
class SyncListSerializerTest extends ListTestBase {

  /**
   * @covers ::denormalize
   */
  public function testDenormalize() {
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/sync-list.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\SyncList $syncList */
    $syncList = $this->serializer->deserialize($jsonData, SyncList::class, 'json');
    $this->assertCount(2, $syncList->getSyncs());
    $expected = [
      'test-456-sync',
      'test-123-sync',
    ];
    $this->assertObjectOrder($expected, $syncList->getSyncsByCreatedTime());
  }

}
