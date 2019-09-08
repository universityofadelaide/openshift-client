<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\ConfigMap;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\ConfigMapNormalizer
 */
class ConfigMapSerializerTest extends TestCase {

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
    $jsonData = file_get_contents(__DIR__ . '/../../../fixtures/configmap.json');
    /** @var \UniversityOfAdelaide\OpenShift\Objects\ConfigMap $configMap */
    $configMap = $this->serializer->deserialize($jsonData, ConfigMap::class, 'json');
    $this->assertEquals('test-config', $configMap->getName());
    $this->assertEquals(['test-label' => 'test label value'], $configMap->getLabels());
    $this->assertEquals(['foo' => 'bar', 'boo' => 'far'], $configMap->getData());
  }

  /**
   * @covers ::normalize
   */
  public function testNormalizer() {
    /** @var \UniversityOfAdelaide\OpenShift\Objects\ConfigMap $configMap */
    $configMap = ConfigMap::create()
      ->setName('test-config')
      ->setLabel(Label::create('test-label', 'test label value'))
      ->setData(['foo' => 'bar', 'boo' => 'far']);

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/configmap.json'), TRUE);
    unset($expected['status']);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($configMap, 'json'), TRUE));
  }

}
