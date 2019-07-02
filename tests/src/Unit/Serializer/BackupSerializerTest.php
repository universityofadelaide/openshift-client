<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Label;
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
    $this->assertEquals('360h0m0s', $backup->getTtl());
    $this->assertEquals(['app' => 'node-5'], $backup->getMatchLabels());
    $this->assertEquals(Phase::COMPLETED, $backup->getPhase());
    $this->assertEquals('2018-11-21T00:16:23Z', $backup->getStartTimestamp());
    $this->assertEquals('2018-11-21T00:16:43Z', $backup->getCompletionTimestamp());
    $this->assertEquals('test 123', $backup->getAnnotation('some.annotation'));
    $this->assertEquals('2018-11-22T00:05:22Z', $backup->getCreationTimestamp());
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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $backup */
    $backup = Backup::create();
    $backup->setName('test-123-backup')
      ->setAnnotation('some.annotation', 'test 123')
      ->setVolumes([
        'shared' => 'node-123-shared',
      ])
      ->setDatabases([$db])
      ->setLabel(Label::create('test-label', 'test label value'));

    $expected = file_get_contents(__DIR__ . '/../../../fixtures/backup.json');
    $this->assertEquals(json_decode($expected), json_decode($this->serializer->serialize($backup, 'json')));
    // Ensure annotations aren't set when empty.
    $backup = Backup::create()->setName('test 123');
    $normalized = $this->serializer->normalize($backup);
    $this->assertArrayNotHasKey('annotations', $normalized['metadata']);
  }

}
