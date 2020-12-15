<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;

/**
 * Class ConvertPaymentAction
 *
 * @see     https://github.com/Payum/Payum/blob/master/src/Payum/AuthorizeNet/Aim/Action/ConvertPaymentAction.php
 * @package Solarix\SyliusAuthorizeNetPlugin\Payum\Action
 */
class ConvertPaymentAction implements ActionInterface
{
  /**
   * {@inheritdoc}
   *
   * @param Convert|mixed $request
   */
  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    /** @var PaymentInterface $payment */
    $payment = $request->getSource();
    /** @var OrderInterface $order */
    $order = $payment->getOrder();

    $details = [];

    $request->setResult($details);

    throw new \LogicException('Not implemented');
  }

  /**
   * {@inheritdoc}
   */
  public function supports($request): bool
  {
    return $request instanceof Convert &&
      $request->getSource() instanceof PaymentInterface &&
      $request->getTo() === 'array';
  }
}
