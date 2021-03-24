<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Route;
use UniversityOfAdelaide\OpenShift\Serializer\OpenShiftSerializerFactory;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Serializer\RouteNormalizer
 */
class RouteSerializerTest extends TestCase {

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
    /** @var \UniversityOfAdelaide\OpenShift\Objects\Route $route */
    $route = Route::create()
      ->setName('route-test')
      ->setHost('route.host')
      ->setPath('/route/path')
      ->setInsecureEdgeTerminationPolicy('Allow')
      ->setTermination('edge')
      ->setToKind('Service')
      ->setToName('svc-test')
      ->setToWeight(50)
      ->setWildcardPolicy('None');

    $expected = json_decode(file_get_contents(__DIR__ . '/../../../fixtures/route.json'), TRUE);
    $this->assertEquals($expected, json_decode($this->serializer->serialize($route, 'json'), TRUE));
  }

}
