<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\ObjectBase;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * Test base for list tests.
 */
abstract class ListTestBase extends TestCase {

  /**
   * The serializer.
   *
   * @var \UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory
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
   * Test the order of restores by name.
   *
   * @param array $expected
   *   The expected order.
   * @param array $objects
   *   The restores.
   */
  protected function assertObjectOrder(array $expected, array $objects) {
    $this->assertEquals($expected, array_map(function (ObjectBase $object) {
      return $object->getName();
    }, $objects));
  }

}
