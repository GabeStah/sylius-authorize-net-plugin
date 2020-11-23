<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;

/**
 * Class ConvertPaymentAction
 *
 *
 * @see     https://github.com/Payum/Payum/blob/master/src/Payum/AuthorizeNet/Aim/Action/ConvertPaymentAction.php
 * @package Solarix\SyliusAuthorizeNetPlugin\Payum\Action
 */
class ConvertPaymentAction implements ActionInterface
{
  //  use GatewayAwareTrait;
  //
  //  /**
  //   * {@inheritDoc}
  //   *
  //   * @param Convert $request
  //   */
  //  public function execute($request)
  //  {
  //    RequestNotSupportedException::assertSupports($this, $request);
  //
  //    /** @var PaymentInterface $payment */
  //    $payment = $request->getSource();
  //
  //    throw new \LogicException('Not implemented');
  //  }
  //
  //  /**
  //   * {@inheritDoc}
  //   */
  //  public function supports($request)
  //  {
  //    return $request instanceof Convert &&
  //      $request->getSource() instanceof PaymentInterface &&
  //      $request->getTo() == 'array';
  //  }

  //  /** @var DetailsProviderInterface */
  //  private $detailsProvider;
  //
  //  public function __construct(DetailsProviderInterface $detailsProvider)
  //  {
  //    $this->detailsProvider = $detailsProvider;
  //  }

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

    //    $details = $this->detailsProvider->getDetails($order);
    $details = [];

    $request->setResult($details);
  }

  /**
   * {@inheritdoc}
   */
  public function supports($request): bool
  {
    return $request instanceof Convert &&
      $request->getSource() instanceof PaymentInterface &&
      $request->getTo() === 'array';
    //    return true;
  }
}
