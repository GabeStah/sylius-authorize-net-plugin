<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum;

final class AuthorizeNetApi
{
  /** @var string */
  private $apiKey;

  public function __construct(string $apiKey)
  {
    $this->apiKey = $apiKey;
  }

  public function getApiKey(): string
  {
    return $this->apiKey;
  }
}
