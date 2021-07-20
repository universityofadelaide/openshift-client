<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\SyncNormalizer
 */
class SyncSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/sync.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $sync */
    $sync = $this->serializer->deserialize($jsonData, Sync::class, 'json');
    $this->assertEquals('test-123-sync', $sync->getName());
    $this->assertEquals(['test-label' => 'test label value'], $sync->getLabels());
    $this->assertEquals(Phase::COMPLETED, $sync->getBackupPhase());
    $this->assertEquals(Phase::IN_PROGRESS, $sync->getRestorePhase());
    $this->assertEquals('2021-07-20T04:46:08Z', $sync->getStartTimestamp());
    $this->assertEquals('2021-07-20T04:46:19Z', $sync->getCompletionTimestamp());
    $this->assertEquals('2021-07-20T04:46:08Z', $sync->getCreationTimestamp());
    $this->assertEquals(['shared' => 'node-6-shared'], $sync->getBackupVolumes());
    $this->assertEquals(['shared' => 'node-5-shared'], $sync->getRestoreVolumes());
    $this->assertEquals('2', $sync->getSite());
    $this->assertEquals('6', $sync->getBackupEnv());
    $this->assertEquals('5', $sync->getRestoreEnv());
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database $db */
    $db = $sync->getBackupDatabases()[0];
    $this->assertEquals('default', $db->getId());
    $this->assertEquals('node-6', $db->getSecretName());
    $this->assertEquals([
      'username' => 'DATABASE_USER',
      'password' => 'DATABASE_PASSWORD',
      'database' => 'DATABASE_NAME',
      'hostname' => 'DATABASE_HOST',
      'port' => 'DATABASE_PORT',
    ], $db->getSecretKeys());
    $db = $sync->getRestoreDatabases()[0];
    $this->assertEquals('default', $db->getId());
    $this->assertEquals('node-5', $db->getSecretName());
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
    $backupDb = (new Database())->setId('default')
      ->setSecretName('node-6')
      ->setSecretKeys([
        'username' => 'DATABASE_USER',
        'password' => 'DATABASE_PASSWORD',
        'database' => 'DATABASE_NAME',
        'hostname' => 'DATABASE_HOST',
        'port' => 'DATABASE_PORT',
      ]);
    $restoreDb = (new Database())->setId('default')
      ->setSecretName('node-5')
      ->setSecretKeys([
        'username' => 'DATABASE_USER',
        'password' => 'DATABASE_PASSWORD',
        'database' => 'DATABASE_NAME',
        'hostname' => 'DATABASE_HOST',
        'port' => 'DATABASE_PORT',
      ]);
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Sync $sync */
    $sync = Sync::create()
      ->setName('test-123-sync')
      ->setBackupVolumes([
        'shared' => 'node-6-shared',
      ])
      ->setRestoreVolumes([
        'shared' => 'node-5-shared',
      ])
      ->setBackupEnv('6')
      ->setRestoreEnv('5')
      ->setSite('2')
      ->setBackupDatabases([$backupDb])
      ->setRestoreDatabases([$restoreDb])
      ->setLabel(Label::create('test-label', 'test label value'));

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/sync.json'), TRUE);
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($sync, 'json'), TRUE));
  }

}
