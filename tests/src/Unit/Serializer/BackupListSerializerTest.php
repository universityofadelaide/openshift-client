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
    $this->assertEquals(5, $backupList->getBackupCount());
    $this->assertCount(5, $backupList->getBackups());
    $backupList->addBackup(Backup::create());
    $this->assertEquals(6, $backupList->getBackupCount());
  }

}
