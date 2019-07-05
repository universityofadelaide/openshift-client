<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\RestoreNormalizer
 */
class RestoreSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/restore.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $restore */
    $restore = $this->serializer->deserialize($jsonData, Restore::class, 'json');
    $this->assertEquals('test-restore', $restore->getName());
    $this->assertEquals('test-backup', $restore->getBackupName());
    $this->assertEquals(['site_id' => '123'], $restore->getLabels());
    $this->assertEquals(Phase::COMPLETED, $restore->getPhase());
    $this->assertEquals('2018-11-26T01:42:57Z', $restore->getCreationTimestamp());
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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Restore $restore */
    $restore = Restore::create()
      ->setName('test-restore')
      ->setBackupName('test-backup')
      ->setVolumes([
        'shared' => 'node-123-shared',
      ])
      ->setDatabases([$db])
      ->setLabel(Label::create('site_id', '123'));

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/restore.json'), TRUE);
    // We don't set status on normalization.
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($restore, 'json'), TRUE));
  }

}
