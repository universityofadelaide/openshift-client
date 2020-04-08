<?php

namespace UniversityOfAdelaide\OpenShift\Tests\Unit;

use PHPUnit\Framework\TestCase;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Phase;
use UniversityOfAdelaide\OpenShift\Objects\Label;

/**
 * @coversDefaultClass \UniversityOfAdelaide\OpenShift\Objects\Backups\Backup
 */
class BackupTest extends TestCase {

  /**
   * Tests isManual.
   *
   * @covers ::isManual
   *
   * @dataProvider providerTestIsManual
   */
  public function testIsManual($expected, $label) {
    $backup = new Backup();
    if ($label) {
      $backup->setLabel(Label::create(Backup::MANUAL_LABEL, $label));
    }
    $this->assertEquals($expected, $backup->isManual());
  }

  /**
   * Tests getFriendlyName.
   *
   * @covers ::getFriendlyName
   */
  public function testGetFriendlyName() {
    $backup = new Backup();
    $backup->setName('foo');
    $this->assertEquals('foo', $backup->getFriendlyName());
    $backup->setAnnotation(Backup::FRIENDLY_NAME_ANNOTATION, 'bar');
    $this->assertEquals('bar', $backup->getFriendlyName());
  }

  /**
   * Data provider for testIsManual.
   *
   * @return array
   *   Array of values.
   */
  public function providerTestIsManual() {
    return [
      [FALSE, FALSE],
      [TRUE, '1'],
      [TRUE, 1],
      [FALSE, 0],
      [FALSE, '0'],
      [FALSE, 'foo'],
    ];
  }

}
