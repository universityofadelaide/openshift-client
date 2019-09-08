<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\NetworkPolicy;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\NetworkPolicyNormalizer
 */
class NetworkPolicySerializerTest extends TestCase {

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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\NetworkPolicy $np */
    $np = NetworkPolicy::create()
      ->setIngressMatchLabels(['app' => 'node-19'])
      ->setPodSelectorMatchLabels(['application' => 'datagrid-app'])
      ->setPort(11312)
      ->setName('test-np');

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/networkpolicy.json'), TRUE);
    unset($expected['metadata']['creationTimestamp']);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($np, 'json'), TRUE));
  }

}
