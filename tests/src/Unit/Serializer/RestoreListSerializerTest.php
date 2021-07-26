<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\BackupListNormalizer
 */
class RestoreListSerializerTest extends ListTestBase {

  /**
   * @covers ::denormalize
   */
  public function testDenormalize() {
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/restore-list.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList $restoreList */
    $restoreList = $this->serializer->deserialize($jsonData, RestoreList::class, 'json');
    $this->assertTrue($restoreList->hasRestores());
    $this->assertEquals(3, $restoreList->getRestoreCount());
    $this->assertCount(3, $restoreList->getRestores());
    $expected = [
      'restore-2',
      'restore-3',
      'restore-1',
    ];
    $this->assertObjectOrder($expected, $restoreList->getRestoresByCreatedTime());
  }

}
