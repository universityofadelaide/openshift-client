<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Objects\Backups\Phase
 */
class PhaseTest extends TestCase {

  /**
   * Tests getFriendlyPhase.
   *
   * @covers ::getFriendlyPhase
   *
   * @dataProvider providerTestGetFriendlyPhase
   */
  public function testGetFriendlyPhase($expected, $phase) {
    $this->assertEquals($expected, Phase::getFriendlyPhase($phase));
  }

  /**
   * Data provider for testGetFriendlyPhase.
   *
   * @return array
   *   Array of values.
   */
  public function providerTestGetFriendlyPhase() {
    return [
      ['Completed', 'Completed'],
      ['In Progress', 'InProgress'],
      ['Failed Validation', 'FailedValidation'],
      ['Completed', 'Completed'],
      ['', ''],
    ];
  }

}
