<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\ConfigMap;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Objects\StatefulSet;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\StatefulSetNormalizer
 */
class StatefulSetSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/statefulset.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\StatefulSet $ss */
    $ss = $this->serializer->deserialize($jsonData, StatefulSet::class, 'json');
    $this->assertEquals('test-ss', $ss->getName());
    $this->assertEquals(['application' => 'test'], $ss->getLabels());
    $this->assertEquals([
      'template' => [
        'spec' => [
          'securityContext' => [],
        ],
      ],
    ], $ss->getSpec());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\StatefulSet $ss */
    $ss = StatefulSet::create()
      ->setName('test-ss')
      ->setLabel(Label::create('application', 'test'))
      ->setSpec([
        'template' => [
          'spec' => [
            'securityContext' => [],
          ],
        ],
      ]);

    $expected = trim(file_get_contents(__DIR__ . '/../../../fixtures/statefulsetnormalized.json'));
    $actual = $this->serializer->serialize($ss, 'json');
    // Use assertEquals here as the json assertions will not pick up on the
    // difference between an empty array and empty object.
    $this->assertEquals($expected, $actual);
  }

}
