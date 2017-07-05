<?php

namespace UniversityOfAdelaide\OpenShift;

/**
 * OpenShift client exception.
 */
class ClientException extends \Exception {

  /** @var string */
  private $body;

  public function __construct(
    $message,
    $code,
    \Exception $previous = NULL,
    string $body = ''
  ) {
    parent::__construct($message, $code, $previous);
    $this->body = $body;
  }

  /**
   * Get the associated response body.
   *
   * @return string|null
   *   Returns the response body if set, null otherwise.
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * Check if a response body is set.
   *
   * @return bool
   *   True if the body is set.
   */
  public function hasBody() {
    return $this->body !== NULL;
  }

}
