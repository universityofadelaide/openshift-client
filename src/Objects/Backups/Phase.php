<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines phase constants.
 */
class Phase {

  const NEW = 'New';
  const FAILED_VALIDATION = 'FailedValidation';
  const IN_PROGRESS = 'InProgress';
  const COMPLETED = 'Completed';
  const ENABLED = 'Enabled';
  const FAILED = 'Failed';

  /**
   * Returns the friendly name for a phase.
   *
   * @param string $phase
   *   The phase string.
   *
   * @return string
   *   The friendly string of the phase.
   */
  public static function getFriendlyPhase(string $phase) {
    return implode(' ', preg_split('/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', $phase));
  }

}
