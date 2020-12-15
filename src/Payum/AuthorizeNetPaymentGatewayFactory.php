<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum;

use GuzzleHttp\Client;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\CaptureAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\ConvertPaymentAction;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Action\StatusAction;

final class AuthorizeNetPaymentGatewayFactory extends GatewayFactory
{
  protected function populateConfig(ArrayObject $config): void
  {
    $config->defaults([
      'payum.factory_name' => 'authorize_net_payment',
      'payum.factory_title' => 'Authorize.net Payment',
      'payum.template.obtain_credit_card' =>
        '@SolarixSyliusAuthorizeNetPlugin/obtainCreditCard.html.twig',
      // API template
      //      'payum.template.api_request' =>
      //        '@SolarixSyliusAuthorizeNetPlugin/api_request.html.twig',
      //      'payum.action.authorize' => new AuthorizeAction(),
      //      'payum.action.refund' => new RefundAction(),
      //      'payum.action.cancel' => new CancelAction(),
      //      'payum.action.notify' => new NotifyAction(),
      'payum.action.capture' => new CaptureAction(new Client()),
      'payum.action.convert_payment' => new ConvertPaymentAction(),
      'payum.action.status' => new StatusAction(),
      // Payment form
      //      'payum.action.api.payment_form' => function (ArrayObject $config) {
      //        return new PaymentFormAction($config['payum.template.api_request']);
      //      },
      // Obtain credit card action
      //      'payum.action.obtain_credit_card' => new ObtainCreditCardAction(),
    ]);

    if (false == $config['payum.api']) {
      $config['payum.default_options'] = [
        'api_id' => $_ENV['AUTHORIZE_NET_API_ID'],
        'transaction_key' => $_ENV['AUTHORIZE_NET_TRANSACTION_KEY'],
        'sandbox' => true,
        'payum.template.obtain_credit_card' =>
          '@SolarixSyliusAuthorizeNetPlugin/obtainCreditCard.html.twig',
      ];
      $config->defaults($config['payum . default_options']);
      $config['payum . required_options'] = ['api_id', 'transaction_key'];

      $config['payum . api'] = function (ArrayObject $config) {
        $config->validateNotEmpty($config['payum . required_options']);

        $api = new AuthorizeNetApi(
          $config['api_id'],
          $config['transaction_key']
        );
        $api->setSandbox($config['sandbox']);

        return $api;
      };
    }
  }
}
