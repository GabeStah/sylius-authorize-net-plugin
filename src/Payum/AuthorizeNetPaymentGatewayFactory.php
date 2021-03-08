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
      'payum.action.capture' => new CaptureAction(new Client()),
      'payum.action.convert_payment' => new ConvertPaymentAction(),
      'payum.action.status' => new StatusAction(),
    ]);

    if (false == $config['payum.api']) {
      $config['payum.default_options'] = [
        'api_id' => $config['api_id'] ?? $_ENV['AUTHORIZE_NET_API_ID'],
        'transaction_key' =>
          $config['transaction_key'] ?? $_ENV['AUTHORIZE_NET_TRANSACTION_KEY'],
        'sandbox' => $config['sandbox'] ?? $_ENV['AUTHORIZE_NET_SANDBOX'],
        'payum.template.obtain_credit_card' =>
          '@SolarixSyliusAuthorizeNetPlugin/obtainCreditCard.html.twig',
      ];
      error_log(
        'api_id: ' .
          substr($config['api_id'], null, strlen($config['api_id']) - 3) .
          PHP_EOL,
        3,
        $_SERVER['INIT_CWD'] . '/var/log/dev.log'
      );
      error_log(
        'transaction_key: ' .
          substr(
            $config['transaction_key'],
            null,
            strlen($config['transaction_key']) - 3
          ) .
          PHP_EOL,
        3,
        $_SERVER['INIT_CWD'] . '/var/log/dev.log'
      );
      $config->defaults($config['payum.default_options']);
      $config['payum.required_options'] = ['api_id', 'transaction_key'];

      $config['payum.api'] = function (ArrayObject $config) {
        $config->validateNotEmpty($config['payum.required_options']);

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
