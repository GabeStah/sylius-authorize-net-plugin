<?php

declare(strict_types=1);

namespace Solarix\SyliusAuthorizeNetPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthorizeNetGatewayConfigurationType extends AbstractType
{
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('login_id', TextType::class, [
        'label' =>
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.login_id',
        'constraints' => [
          new NotBlank([
            'message' =>
              'solarix_sylius_authorize_net_plugin.form.gateway_configuration.error.login_id.not_blank',
            'groups' => 'sylius',
          ]),
        ],
      ])
      ->add('gateway_id', TextType::class, [
        'label' =>
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.gateway_id',
        'constraints' => [
          new NotBlank([
            'message' =>
              'solarix_sylius_authorize_net_plugin.form.gateway_configuration.error.gateway_id.not_blank',
            'groups' => 'sylius',
          ]),
        ],
      ])
      ->add('transaction_key', TextType::class, [
        'label' =>
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.transaction_key',
        'constraints' => [
          new NotBlank([
            'message' =>
              'solarix_sylius_authorize_net_plugin.form.gateway_configuration.error.transaction_key.not_blank',
            'groups' => 'sylius',
          ]),
        ],
      ])
      ->add('auto_capture', ChoiceType::class, [
        'label' =>
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.auto_capture',
        'choices' => [
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.no' => 0,
          'solarix_sylius_authorize_net_plugin.form.gateway_configuration.authorize_net.yes' => 1,
        ],
      ])
      ->add('use_authorize', HiddenType::class, [
        'data' => true,
      ]);
  }
}
