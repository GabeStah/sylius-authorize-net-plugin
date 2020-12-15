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

    $loader = new YamlFileLoader(
      $container,
      new FileLocator(dirname(__DIR__) . '/Resources/config')
    );
    $loader->load('services.yaml');
  }
}
