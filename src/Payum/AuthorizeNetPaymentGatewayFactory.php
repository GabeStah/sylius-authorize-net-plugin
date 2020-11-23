<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum;

use GuzzleHttp\Client;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\AuthorizeAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\CancelAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\CaptureAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\ConvertPaymentAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\NotifyAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\RefundAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\StatusAction;

final class AuthorizeNetPaymentGatewayFactory extends GatewayFactory
{
  protected function populateConfig(ArrayObject $config): void
  {
    $config->defaults([
      'payum.factory_name' => 'authorize_net_payment',
      'payum.factory_title' => 'Authorize.net Payment',
      'payum.action.capture' => new CaptureAction(new Client()),
      'payum.action.authorize' => new AuthorizeAction(),
      'payum.action.refund' => new RefundAction(),
      'payum.action.cancel' => new CancelAction(),
      'payum.action.notify' => new NotifyAction(),
      'payum.action.status' => new StatusAction(),
      'payum.action.convert_payment' => new ConvertPaymentAction(),
    ]);

    $config['payum.api'] = function () use ($config) {
      return new AuthorizeNetApi($config['login_id']);
    };
  }
}
