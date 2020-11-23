<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use App\Entity\Payment\Payment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

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

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getFirstModel();

    $details = $payment->getDetails();

    if (200 === $details['status']) {
      $request->markCaptured();

      return;
    }

    if (400 === $details['status']) {
      $request->markFailed();

      return;
    }
  }

  public function supports($request): bool
  {
    return $request instanceof GetStatus &&
      $request->getFirstModel() instanceof Payment;
  }
}
