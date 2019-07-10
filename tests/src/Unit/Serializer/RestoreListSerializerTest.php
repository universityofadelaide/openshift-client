<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\BackupListNormalizer
 */
class RestoreListSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/restore-list.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList $restoreList */
    $restoreList = $this->serializer->deserialize($jsonData, RestoreList::class, 'json');
    $this->assertTrue($restoreList->hasRestores());
    $this->assertEquals(3, $restoreList->getRestoreCount());
    $this->assertCount(3, $restoreList->getRestores());
    $expected = [
      'restore-2',
      'restore-3',
      'restore-1',
    ];
    $this->assertRestoreOrder($expected, $restoreList->getRestoresByCreatedTime());
  }

  /**
   * Test the order of restores by name.
   *
   * @param array $expected
   *   The expected order.
   * @param array $restores
   *   The restores.
   */
  protected function assertRestoreOrder(array $expected, array $restores) {
    $this->assertEquals($expected, array_map(function (Restore $restore) {
      return $restore->getName();
    }, $restores));
  }

}
