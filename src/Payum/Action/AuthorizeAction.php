<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Authorize;

class AuthorizeAction implements ActionInterface, GatewayAwareInterface
{
  use GatewayAwareTrait;

  /**
   * {@inheritDoc}
   *
   * @param Authorize $request
   */
  public function execute($request)
  {
    RequestNotSupportedException::assertSupports($this, $request);

    $model = ArrayObject::ensureArrayObject($request->getModel());

    throw new \LogicException('Not implemented');
  }

  /**
   * {@inheritDoc}
   */
  public function supports($request)
  {
    return $request instanceof Authorize &&
      $request->getModel() instanceof \ArrayAccess;
  }
}
