# Solarix: SyliusAuthorizeNetPlugin

The SyliusAuthorizeNetPlugin adds [Authorize.net](https://www.authorize.net/) payment gateway support to the [Sylius](https://sylius.com/) platform.

This plugin was created out of a need for an Authorize.net + Sylius gateway that is compatible with the modern Authorize.net API.  Unfortunately, both the existing [Authorize.net Payum](https://github.com/Payum/Payum/tree/master/src/Payum/AuthorizeNet/Aim) integration as well as [Ben Borla's SyliusAuthorizeNet](https://github.com/benborla/sylius-authorize-net) plugin rely on the deprecated [Authorize.net AIM](https://developer.authorize.net/api/upgrade_guide.html#aim) API.  A new Payum gateway and integrated Sylius plugin was the only way to create a functional Authorize.net solution.

## Features

- Sylius plugin for single-step installation
- Internal [Payum](https://github.com/Payum/Payum) integration to rely on existing Sylius statemachines

## Installation

Install in the target project:

```bash
composer install solarix/sylius-authorize-net-plugin
```

## Configuration

1. Add a new Sylius PaymentMethod
2. Add Authorize.net credentials to the appropriate fields

## Usage

*TODO*