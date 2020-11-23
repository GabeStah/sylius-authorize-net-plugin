<?php

namespace Solarix\SyliusAuthorizeNetPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SolarixSyliusAuthorizeNetExtension extends Extension
{
  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    $configs = $this->processConfiguration(
      $this->getConfiguration([], $container),
      $configs
    );

    $container->setParameter(
      'solarix_sylius_authorize_net_plugin.line_item_image.imagine_filter',
      $configs['line_item_image']['imagine_filter']
    );
    $container->setParameter(
      'solarix_sylius_authorize_net_plugin.line_item_image.fallback_image',
      $configs['line_item_image']['fallback_image']
    );

    $loader = new YamlFileLoader(
      $container,
      new FileLocator(dirname(__DIR__) . '/Resources/config')
    );
    $loader->load('services.yaml');
  }
}
