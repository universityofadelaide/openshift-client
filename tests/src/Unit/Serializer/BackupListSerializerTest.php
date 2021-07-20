<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\BackupListNormalizer
 */
class BackupListSerializerTest extends ListTestBase {

  /**
   * @covers ::denormalize
   */
  public function testDenormalize() {
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/backup-list.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList $backupList */
    $backupList = $this->serializer->deserialize($jsonData, BackupList::class, 'json');
    $this->assertTrue($backupList->hasBackups());
    $this->assertEquals(2, $backupList->getBackupCount());
    $this->assertCount(2, $backupList->getBackups());
    $this->assertCount(1, $backupList->getCompletedBackups());
    $expected = [
      'node-3-backup',
      'node-4-backup',
    ];
    $this->assertObjectOrder($expected, $backupList->getBackupsByStartTime());
    $this->assertObjectOrder(array_reverse($expected), $backupList->getBackupsByStartTime('ASC'));
    unset($expected[1]);
    $this->assertObjectOrder(array_values($expected), $backupList->getCompletedBackupsByStartTime());
  }

}
