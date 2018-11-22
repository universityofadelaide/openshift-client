<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\BackupNormalizer
 */
class BackupSerializerTest extends TestCase {

  /**
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\Serializer
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
    $backup = $backupList->getBackups()[0];
    $this->assertEquals('node-5-backup', $backup->getName());
    $this->assertEquals(['ark.heptio.com/storage-location' => 'default'], $backup->getLabels());
    $this->assertEquals([], $backup->getHooks());
    $this->assertEquals('360h0m0s', $backup->getTtl());
    $this->assertEquals(['app' => 'node-5'], $backup->getMatchLabels());
    $this->assertEquals(Phase::COMPLETED, $backup->getPhase());
    $this->assertEquals('2018-11-21T00:16:23Z', $backup->getStartTimestamp());
    $this->assertEquals('2018-11-21T00:16:43Z', $backup->getCompletionTimestamp());
    $this->assertEquals('2018-12-21T00:16:23Z', $backup->getExpires());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    $backup = Backup::create();
    $backup->setName('test-123-backup')
      ->setTtl('100h20m0s')
      ->setMatchLabels(['app' => 'test-123'])
      ->setLabel('test-label', 'test label value');

    $expected = file_get_contents(__DIR__ . '/../../../fixtures/backup.json');
    $this->assertEquals(json_decode($expected), json_decode($this->serializer->serialize($backup, 'json')));
  }

}
