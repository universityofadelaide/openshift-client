<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\BackupListNormalizer
 */
class BackupListSerializerTest extends TestCase {

  /**
   * The serializer.
   *
   * @var \UniversityOfAdelaide\OpenShift\Objects\Serializer\OpenShiftSerializerFactory
   */
  protected $serializer;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->serializer = OpenShiftSerializerFactory::create();
  }

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
    $this->assertBackupOrder($expected, $backupList->getBackupsByStartTime());
    $this->assertBackupOrder(array_reverse($expected), $backupList->getBackupsByStartTime('ASC'));
    unset($expected[1]);
    $this->assertBackupOrder(array_values($expected), $backupList->getCompletedBackupsByStartTime());
  }

  /**
   * Test the order of backups by name.
   *
   * @param array $expected
   *   The expected order.
   * @param array $backups
   *   The backups.
   */
  protected function assertBackupOrder(array $expected, array $backups) {
    $this->assertEquals($expected, array_map(function (Backup $backup) {
      return $backup->getName();
    }, $backups));
  }

}
