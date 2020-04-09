<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/backup.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $backup */
    $backup = $this->serializer->deserialize($jsonData, Backup::class, 'json');
    $this->assertEquals('test-123-backup', $backup->getName());
    $this->assertEquals(['test-label' => 'test label value'], $backup->getLabels());
    $this->assertEquals(Phase::COMPLETED, $backup->getPhase());
    $this->assertEquals('2018-11-21T00:16:23Z', $backup->getStartTimestamp());
    $this->assertEquals('2018-11-21T00:16:43Z', $backup->getCompletionTimestamp());
    $this->assertEquals('test 123', $backup->getAnnotation('some.annotation'));
    $this->assertEquals('2019-07-03T02:12:48Z', $backup->getCreationTimestamp());
    $this->assertEquals(['shared' => 'node-123-shared'], $backup->getVolumes());
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Database $db */
    $db = $backup->getDatabases()[0];
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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup $backup */
    $backup = Backup::create()
      ->setName('test-123-backup')
      ->setAnnotation('some.annotation', 'test 123')
      ->setVolumes([
        'shared' => 'node-123-shared',
      ])
      ->addDatabase($db)
      ->setLabel(Label::create('test-label', 'test label value'));

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/backup.json'), TRUE);
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($backup, 'json'), TRUE));
    // Ensure annotations aren't set when empty.
    $backup = Backup::create()->setName('test 123');
    $normalized = $this->serializer->normalize($backup);
    $this->assertArrayNotHasKey('annotations', $normalized['metadata']);
  }

}
