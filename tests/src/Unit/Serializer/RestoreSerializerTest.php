<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
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
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    $restore = Restore::create();
    $restore->setName('test-restore')
      ->setBackupName('test-backup')
      ->setLabel(Label::create('site_id', '123'));

    $expected = file_get_contents(__DIR__ . '/../../../fixtures/restore.json');
    $expected = json_decode($expected, TRUE);
    // We don't set status on normalization.
    unset($expected['status']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($restore, 'json'), TRUE));
  }

}
