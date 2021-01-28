<?php

namespace Solarix\SyliusAuthorizeNetPlugin\Payum;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Payum\Core\Model\CreditCardInterface;
use Solarix\SyliusAuthorizeNetPlugin\Payum\Response\AuthorizeNetResponse;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class AuthorizeNetApi
{
  const PRODUCTION_URL = 'https://api2.authorize.net';
  const SANDBOX_URL = 'https://apitest.authorize.net';

  /** @var string */
  private $apiId;
  /** @var CreditCardInterface */
  private $creditCard;
  /** @var OrderInterface */
  private $order;
  /** @var PaymentInterface */
  private $payment;
  /**
   * @var string
   */
  private $transactionKey;
  /**
   * @var boolean
   */
  private $sandbox = true;

  /**
   * AuthorizeNetApi constructor.
   *
   * @param string $apiId
   * @param string $transactionKey
   */
  public function __construct(string $apiId, string $transactionKey)
  {
    $this->setApiId($apiId);
    $this->setTransactionKey($transactionKey);
  }

  /**
   * @return AuthorizeNetResponse
   */
  public function authorizeAndCapture(): AuthorizeNetResponse
  {
    return $this->chargeCreditCard();
  }

  /**
   * @return OrderInterface
   */
  public function getOrder(): OrderInterface
  {
    return $this->order;
  }

  /**
   * @param OrderInterface $order
   *
   * @return AuthorizeNetApi
   */
  public function setOrder(OrderInterface $order): self
  {
    $this->order = $order;
    return $this;
  }

  /**
   * @return PaymentInterface
   */
  public function getPayment(): PaymentInterface
  {
    return $this->payment;
  }

  /**
   * @param PaymentInterface $payment
   *
   * @return AuthorizeNetApi
   */
  public function setPayment(PaymentInterface $payment): self
  {
    $this->payment = $payment;
    return $this;
  }

  /**
   * @return CreditCardInterface
   */
  public function getCreditCard(): CreditCardInterface
  {
    return $this->creditCard;
  }

  /**
   * @param CreditCardInterface $creditCard
   *
   * @return AuthorizeNetApi
   */
  public function setCreditCard(CreditCardInterface $creditCard): self
  {
    $this->creditCard = $creditCard;
    return $this;
  }

  /**
   * @return string
   */
  public function getApiId(): string
  {
    return $this->apiId;
  }

  /**
   * @param string $apiId
   *
   * @return AuthorizeNetApi
   */
  public function setApiId(string $apiId): self
  {
    $this->apiId = $apiId;
    return $this;
  }

  /**
   * @return string
   */
  public function getTransactionKey(): string
  {
    return $this->transactionKey;
  }

  /**
   * @param string $transactionKey
   *
   * @return AuthorizeNetApi
   */
  public function setTransactionKey(string $transactionKey): self
  {
    $this->transactionKey = $transactionKey;
    return $this;
  }

  /**
   * @return bool
   */
  public function isSandbox(): bool
  {
    return $this->sandbox;
  }

  /**
   * @param bool $sandbox
   *
   * @return AuthorizeNetApi
   */
  public function setSandbox(bool $sandbox): self
  {
    $this->sandbox = $sandbox;
    return $this;
  }

  /**
   * @return AuthorizeNetResponse
   */
  private function chargeCreditCard(): AuthorizeNetResponse
  {
    /* Create a merchantAuthenticationType object with authentication details
     retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName($this->getApiId());
    $merchantAuthentication->setTransactionKey($this->getTransactionKey());

    // Set the transaction's refId
    $refId = 'ref' . time();

    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber($this->getCreditCard()->getNumber());
    $creditCard->setExpirationDate(
      $this->getCreditCard()
        ->getExpireAt()
        ->format('Y-m')
    );
    $creditCard->setCardCode($this->getCreditCard()->getSecurityCode());

    // Add the payment data to a paymentType object
    $paymentType = new AnetAPI\PaymentType();
    $paymentType->setCreditCard($creditCard);

    // Create order information
    $order = new AnetAPI\OrderType();
    $order->setInvoiceNumber($this->getOrder()->getNumber());
    $order->setDescription('Raritan Order: #' . $this->getOrder()->getNumber());

    // Set the customer's Bill To address
    $customerAddress = new AnetAPI\CustomerAddressType();
    $customerAddress->setFirstName(
      $this->getOrder()
        ->getBillingAddress()
        ->getFirstName()
    );
    $customerAddress->setLastName(
      $this->getOrder()
        ->getBillingAddress()
        ->getLastName()
    );
    $customerAddress->setCompany(
      $this->getOrder()
        ->getBillingAddress()
        ->getCompany()
    );
    $customerAddress->setAddress(
      $this->getOrder()
        ->getBillingAddress()
        ->getStreet()
    );
    $customerAddress->setCity(
      $this->getOrder()
        ->getBillingAddress()
        ->getCity()
    );
    $customerAddress->setState(
      $this->getOrder()
        ->getBillingAddress()
        ->getProvinceCode()
    );
    $customerAddress->setZip(
      $this->getOrder()
        ->getBillingAddress()
        ->getPostcode()
    );
    $customerAddress->setCountry(
      $this->getOrder()
        ->getBillingAddress()
        ->getCountryCode()
    );

    // Set the customer's identifying information
    $customerData = new AnetAPI\CustomerDataType();
    $customerData->setType('individual');
    $customerData->setEmail(
      $this->getOrder()
        ->getCustomer()
        ->getEmail()
    );

    // Add values for transaction settings
    $duplicateWindowSetting = new AnetAPI\SettingType();
    $duplicateWindowSetting->setSettingName('duplicateWindow');
    $duplicateWindowSetting->setSettingValue('60');

    // Create a TransactionRequestType object and add the previous objects to it
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType('authCaptureTransaction');
    $transactionRequestType->setAmount($this->getPayment()->getAmount() / 100);
    $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($paymentType);
    $transactionRequestType->setBillTo($customerAddress);
    $transactionRequestType->setCustomer($customerData);
    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

    // Assemble the complete transaction request
    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setTransactionRequest($transactionRequestType);

    // Create the controller and get the response
    $controller = new AnetController\CreateTransactionController($request);
    $apiResponse = $controller->executeWithApiResponse(
      $this->isSandbox() ? static::SANDBOX_URL : static::PRODUCTION_URL
    );

    $response = new AuthorizeNetResponse();
    // Assume unsuccessful until defined otherwise
    $response->setStatus(AuthorizeNetResponse::STATUS_TYPE_ERROR);

    if ($apiResponse != null) {
      /** @var \net\authorize\api\contract\v1\TransactionResponseType $transactionResponse */
      $transactionResponse = $apiResponse->getTransactionResponse();

      // Check to see if the API request was successfully received and acted upon
      if ($apiResponse->getMessages()->getResultCode() == 'Ok') {
        // Since the API request was successful, look for a transaction response
        // and parse it to display the results of authorizing the card

        if (
          $transactionResponse != null &&
          $transactionResponse->getMessages() != null
        ) {
          $response->setStatus(AuthorizeNetResponse::STATUS_TYPE_SUCCESS);
        } else {
          $response->setStatus(AuthorizeNetResponse::STATUS_TYPE_ERROR);
          if ($transactionResponse->getErrors() != null) {
            /** @var  $error \net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType */
            foreach ($transactionResponse->getErrors() as $error) {
              $response->addError($error->getErrorText());
            }
          }
        }
        // Or, print errors if the API request wasn't successful
      } else {
        $response->setStatus(AuthorizeNetResponse::STATUS_TYPE_FAILED_REQUEST);

        if (
          $transactionResponse != null &&
          $transactionResponse->getErrors() != null
        ) {
          /** @var  $error \net\authorize\api\contract\v1\TransactionResponseType\ErrorsAType\ErrorAType */
          foreach ($transactionResponse->getErrors() as $error) {
            $response->addError($error->getErrorText());
          }
        } else {
          $response->addError(
            $apiResponse
              ->getMessages()
              ->getMessage()[0]
              ->getText()
          );
        }
      }
    } else {
      $response->setStatus(AuthorizeNetResponse::STATUS_TYPE_NO_RESPONSE);
      $response->addError('Authorize.net failed to respond.');
    }
    return $response;
  }
}
