<?php

namespace Solarix\SyliusAuthorizeNetPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Solarix\SyliusAuthorizeNetPlugin\DependencyInjection\Compiler\PayumGatewayConfigOverride;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SolarixSyliusAuthorizeNetPlugin extends Bundle
{
  use SyliusPluginTrait;

  public function build(ContainerBuilder $container): void
  {
    $container->addCompilerPass(
      new PayumGatewayConfigOverride([
        'authorize_net' => [
          'payum.template.layout' => '@SyliusPayum/layout.html.twig',
        ],
      ])
    );

    parent::build($container);
  }
}
