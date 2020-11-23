<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Hpa;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\HpaNormalizer
 */
class HpaSerializerTest extends TestCase {

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
   * @covers ::normalize
   */
  public function testNormalizer() {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Hpa $hpa */
    $hpa = Hpa::create()
      ->setMinReplicas(1)
      ->setMaxReplicas(2)
      ->setTargetCpu(80)
      ->setName('test-hpa');

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/hpa.json'), TRUE);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($hpa, 'json'), TRUE));
  }

}
