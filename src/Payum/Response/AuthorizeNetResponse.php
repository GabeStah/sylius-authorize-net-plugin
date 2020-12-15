<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Response;

class AuthorizeNetResponse
{
  const STATUS_TYPE_ERROR = 'error';
  const STATUS_TYPE_FAILED_REQUEST = 'failed_request';
  const STATUS_TYPE_NO_RESPONSE = 'no_response';
  const STATUS_TYPE_PENDING = 'pending';
  const STATUS_TYPE_SUCCESS = 'success';

  /** @var string[]|null */
  private $errors;

  /** @var string */
  private $status = 'processing';

  /**
   * Does response indicate a success.
   *
   * @return bool
   */
  public function isSuccess(): bool
  {
    return !$this->hasErrors() &&
      $this->getStatus() === static::STATUS_TYPE_SUCCESS;
  }

  /**
   * @return string
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * @param string $status
   *
   * @return AuthorizeNetResponse
   */
  public function setStatus(string $status): AuthorizeNetResponse
  {
    $this->status = $status;
    return $this;
  }

  /**
   * Add error message.
   *
   * @param string $value
   *
   * @return $this
   */
  public function addError(string $value): AuthorizeNetResponse
  {
    $this->errors[] = $value;
    return $this;
  }

  /**
   * @return string[]|null
   */
  public function getErrors(): ?array
  {
    return $this->errors;
  }

  /**
   * Response has errors.
   *
   * @return bool
   */
  public function hasErrors(): bool
  {
    return $this->errors !== null;
  }

  /**
   * @param string[]|null $errors
   *
   * @return AuthorizeNetResponse
   */
  public function setErrors(?array $errors): AuthorizeNetResponse
  {
    $this->errors = $errors;
    return $this;
  }
}
