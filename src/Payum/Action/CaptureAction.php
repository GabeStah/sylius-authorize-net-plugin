<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use GuzzleHttp\Client;
use LogicException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Request\ObtainCreditCard;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Api\Api;
use Solarix\SyliusAuthorizeNetPlugin\Payum\AuthorizeNetApi;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Request\PaymentForm;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

/**
 * Class CaptureAction
 *
 * @see     https://github.com/Payum/Payum/blob/master/src/Payum/AuthorizeNet/Aim/Action/CaptureAction.php
 * @package Solarix\SyliusAuthorizeNetPlugin\Payum\Action
 */
final class CaptureAction implements
  ActionInterface,
  ApiAwareInterface,
  GatewayAwareInterface
{
  use ApiAwareTrait;
  use GatewayAwareTrait;

  /** @var Client */
  private $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
    $this->apiClass = AuthorizeNetApi::class;
  }

  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    $model = ArrayObject::ensureArrayObject($request->getModel());

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getModel();

    if (false == $model->validateNotEmpty(['card_num', 'exp_date'], false)) {
      try {
        $obtainCreditCard = new ObtainCreditCard($request->getToken());
        $obtainCreditCard->setModel($request->getFirstModel());
        $obtainCreditCard->setModel($request->getModel());
        $this->gateway->execute($obtainCreditCard);
        $card = $obtainCreditCard->obtain();

        /** @var AuthorizeNetApi $api */
        $api = clone $this->api;
        $api
          ->setPayment($payment)
          ->setOrder($payment->getOrder())
          ->setCreditCard($card);
        $response = $api->authorizeAndCapture();

        if ($response->hasErrors()) {
          $payment->setDetails([
            'status' => $response->getStatus(),
            'errors' => $response->getErrors(),
          ]);
        } else {
          $payment->setDetails(['status' => $response->getStatus()]);
        }
      } catch (RequestNotSupportedException $e) {
        throw new LogicException(
          'Credit card details has to be set explicitly or there has to be an action that supports ObtainCreditCard request.'
        );
      }
    }
  }

  public function supports($request): bool
  {
    return $request instanceof Capture &&
      $request->getModel() instanceof SyliusPaymentInterface;
  }
}
