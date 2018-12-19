<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Backups\SyncEnvironment;
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
    $this->assertEquals('sync-sample', $sync->getName());
    $this->assertEquals('node-9-shared', $sync->getSource()->getPersistentVolumeClaim());
    $this->assertEquals('node-9', $sync->getSource()->getSecret());
    $this->assertEquals('node-10-shared', $sync->getTarget()->getPersistentVolumeClaim());
    $this->assertEquals('node-10', $sync->getTarget()->getSecret());
    $this->assertEquals('2018-11-26T01:42:57Z', $sync->getCreationTimestamp());
    $this->assertEquals('Completed', $sync->getPhase());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    $sync = Sync::createFromSourceAndTarget(
      SyncEnvironment::createFromPvcAndSecret('node-9-shared', 'node-9'),
      SyncEnvironment::createFromPvcAndSecret('node-10-shared', 'node-10')
    )->setCreationTimestamp('2018-11-26T01:42:57Z')->setName('sync-sample');

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/sync.json'), TRUE);
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, $this->serializer->normalize($sync));
  }

}
