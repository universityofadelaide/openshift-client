<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
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
    $this->assertEquals(['test-label' => 'test label value'], $schedule->getLabels());
    $this->assertEquals('test-schedule', $schedule->getName());
    $this->assertEquals('2018-11-21T00:16:43Z', $schedule->getLastExecuted());
    $this->assertEquals('0 2 * * *', $schedule->getSchedule());
    $this->assertEquals(['shared' => 'node-123-shared'], $schedule->getVolumes());
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database $db */
    $db = $schedule->getDatabases()[0];
    $this->assertEquals('default', $db->getId());
    $this->assertEquals('node-123', $db->getSecretName());
    $this->assertEquals([
      'username' => 'DATABASE_USER',
      'password' => 'DATABASE_PASSWORD',
      'database' => 'DATABASE_NAME',
      'hostname' => 'DATABASE_HOST',
      'port' => 'DATABASE_PORT',
    ], $db->getSecretKeys());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    $db = (new Database())->setId('default')
      ->setSecretName('node-123')
      ->setSecretKeys([
        'username' => 'DATABASE_USER',
        'password' => 'DATABASE_PASSWORD',
        'database' => 'DATABASE_NAME',
        'hostname' => 'DATABASE_HOST',
        'port' => 'DATABASE_PORT',
      ]);
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup $scheduled */
    $scheduled = ScheduledBackup::create();
    $scheduled->setName('test-schedule')
      ->setVolumes([
        'shared' => 'node-123-shared',
      ])
      ->addDatabase($db)
      ->setLastExecuted('2018-11-29T05:00:47Z')
      ->setSchedule('0 2 * * *')
      ->setRetention(7)
      ->setLabel(Label::create('test-label', 'test label value'));

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/schedule.json'), TRUE);
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, $this->serializer->normalize($scheduled));
  }

}
