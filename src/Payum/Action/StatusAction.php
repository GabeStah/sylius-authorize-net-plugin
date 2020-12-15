<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use App\Entity\Payment\Payment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Response\AuthorizeNetResponse;

/**
 * Class StatusAction
 *
 * @see     https://github.com/Payum/Payum/blob/master/src/Payum/AuthorizeNet/Aim/Action/StatusAction.php
 * @package Solarix\SyliusAuthorizeNetPlugin\Payum\Action
 */
final class StatusAction implements ActionInterface
{
  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    $model = ArrayObject::ensureArrayObject($request->getModel());

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getFirstModel();

    $details = $payment->getDetails();

    switch ($details['status']) {
      case AuthorizeNetResponse::STATUS_TYPE_SUCCESS:
        $request->markCaptured();
        return;
      case AuthorizeNetResponse::STATUS_TYPE_ERROR:
        $request->markFailed();
        return;
      case AuthorizeNetResponse::STATUS_TYPE_PENDING:
        $request->markPending();
        return;
      default:
      // noop
    }
  }

  public function supports($request): bool
  {
    return $request instanceof GetStatus &&
      $request->getFirstModel() instanceof Payment;
  }
}
