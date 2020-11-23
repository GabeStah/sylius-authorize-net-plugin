<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Solarix\SyliusAuthorizeNetPlugin\Payum\AuthorizeNetApi;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;
use Payum\Core\Request\Capture;

/**
 * Class CaptureAction
 *
 * @see     https://github.com/Payum/Payum/blob/master/src/Payum/AuthorizeNet/Aim/Action/CaptureAction.php
 * @package Solarix\SyliusAuthorizeNetPlugin\Payum\Action
 */
final class CaptureAction implements ActionInterface, ApiAwareInterface
{
  /** @var Client */
  private $client;
  /** @var AuthorizeNetApi */
  private $api;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getModel();

    try {
      $response = $this->client->request(
        'POST',
        'https://raritan-ecommerce.free.beeceptor.com',
        [
          'body' => json_encode([
            'price' => $payment->getAmount(),
            'currency' => $payment->getCurrencyCode(),
            'api_key' => $this->api->getApiKey(),
          ]),
        ]
      );
    } catch (RequestException $exception) {
      $response = $exception->getResponse();
    } finally {
      $payment->setDetails(['status' => $response->getStatusCode()]);
    }
  }

  public function supports($request): bool
  {
    return $request instanceof Capture &&
      $request->getModel() instanceof SyliusPaymentInterface;
  }

  public function setApi($api): void
  {
    if (!$api instanceof AuthorizeNetApi) {
      throw new UnsupportedApiException(
        'Not supported. Expected an instance of ' . AuthorizeNetApi::class
      );
    }

    $this->api = $api;
  }
}
