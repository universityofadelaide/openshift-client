<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\ScheduledBackupNormalizer
 */
class ScheduledBackupSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/schedule.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup $schedule */
    $schedule = $this->serializer->deserialize($jsonData, ScheduledBackup::class, 'json');
    $this->assertEquals('test-schedule', $schedule->getName());
    $this->assertEquals('360h0m0s', $schedule->getTtl());
    $this->assertEquals(['app' => 'node-9'], $schedule->getMatchLabels());
    $this->assertEquals(Phase::ENABLED, $schedule->getPhase());
    $this->assertEquals('2018-11-29T05:00:47Z', $schedule->getLastBackup());
    $this->assertEquals('*/5 * * * *', $schedule->getSchedule());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    $backup = ScheduledBackup::create();
    $backup->setName('test-schedule')
      ->setTtl('360h0m0s')
      ->setMatchLabels(['app' => 'node-9'])
      ->setLastBackup('2018-11-29T05:00:47Z')
      ->setSchedule('*/5 * * * *')
      ->setPhase(Phase::ENABLED);

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/schedule.json'), TRUE);
    unset($expected['status']);
    $this->assertEquals($expected, $this->serializer->normalize($backup));
  }

}
