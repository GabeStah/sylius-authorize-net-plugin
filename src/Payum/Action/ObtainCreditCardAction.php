<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum\Action;

use Payum\Core\Bridge\Symfony\Action\ObtainCreditCardAction as ObtainCreditCardActionBase;

class ObtainCreditCardAction extends ObtainCreditCardActionBase
{
  public function execute($request): void
  {
    parent::execute($request);
  }
}
