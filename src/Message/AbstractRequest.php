<?php

namespace Omnipay\Cybersource\Message;

use CybsClient;
use CybsSoapClient;
use Guzzle\Http\ClientInterface;
use Omnipay\Cybersource\BankAccount;
use stdClass;
use Symfony\Component\HttpFoundation\Request as HttpRequest;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $namespace = "http://ics2ws.com/";

    const LIVE_ENDPOINT = 'https://ics2ws.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.109.wsdl';
    const TEST_ENDPOINT = 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.109.wsdl';

    const VERSION = '0.1';
    const API_VERSION = '1.109';

    /**
     * @var \stdClass The generated SOAP request, saved immediately before a transaction is run.
     */
    protected $request;

    /**
     * @var \stdClass The retrieved SOAP response, saved immediately after a transaction is run.
     */
    protected $response;

    /**
     * @var float The amount of time in seconds to wait for both a connection and a response. Total potential wait time is this value times 2 (connection + response).
     */
    public $timeout = 10;


	/**
	 * @var CybsSoapClient
	 */
    public $client;

    public function __construct( ClientInterface $httpClient, HttpRequest $httpRequest ) {
	    $this->httpClient = $httpClient;
	    $this->httpRequest = $httpRequest;
    }


	public function initialize( array $parameters = array() ) {

	    $initialize = parent::initialize( $parameters );

	    $this->client = new CybsClient([], [
		    'merchant_id' => $this->getMerchantId(),
		    'transaction_key' => $this->getTransactionKey(),
		    'wsdl' => $this->getTestMode() ? self::TEST_ENDPOINT : self::LIVE_ENDPOINT,
	    ]);

	    return $initialize;
    }

    /**
     * @param mixed $data
     *
     * @return CommonResponse
     */
    public function sendData($data)
    {
        $client = $this->client;

        $reply = $client->runTransaction($data);

        return new CommonResponse($this, $reply);
    }

	/**
	 * @param string $merchantId
	 *
	 * @return $this
	 */
    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchantId', $merchantId);
        return $this;
    }

    /**
     * return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

	/**
	 * @param string $username
	 *
	 * @return $this
	 */
    public function setUsername($username)
    {
        $this->setParameter('username', $username);
	    return $this;
    }

    /**
     * return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

	/**
	 * @param string $password
	 *
	 * @return $this
	 */
    public function setPassword($password)
    {
        $this->setParameter('password', $password);
	    return $this;
    }

    /**
     * return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

	/**
	 * @param string $transactionKey
	 *
	 * @return $this
	 */
    public function setTransactionKey($transactionKey)
    {
        $this->setParameter('transactionKey', $transactionKey);
	    return $this;
    }

    /**
     * return string
     */
    public function getTransactionKey()
    {
        return $this->getParameter('transactionKey');
    }

    /**
     *
     * @param string $merchantReferenceCode
     */
    public function setMerchantReferenceCode($merchantReferenceCode)
    {
        $this->setParameter('merchantReferenceCode', $merchantReferenceCode);
    }

    /**
     * return string
     */
    public function getMerchantReferenceCode()
    {
        return $this->getParameter('merchantReferenceCode');
    }

    public function getDeviceFingerPrint(){
        return $this->getParameter('deviceFingerprintID');
    }

    public function setDeviceFingerPrint($value){
        return $this->setParameter('deviceFingerprintID', $value);
    }

    public function getMerchantCustomerId(){
        return $this->getParameter('merchantCustId');
    }

    public function setMerchantCustomerId($value){
        return $this->setParameter('merchantCustId', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? self::TEST_ENDPOINT : self::LIVE_ENDPOINT;
    }

    public function getCardTypes()
    {
        return array(
            'visa' => '001',
            'mastercard' => '002',
            'amex' => '003',
            'discover' => '004',
            'diners_club' => '005',
            'carte_blanche' => '006',
            'jcb' => '007',
        );
    }

    public function getCardType()
    {
        $types = $this->getCardTypes();
        $brand = $this->getCard()->getBrand();
        return empty($types[$brand]) ? null : $types[$brand];
    }

	public function createRequest($merchantReferenceCode = null)
	{
		$request = new stdClass();
		$request->merchantID = $this->getMerchantId();
		$request->merchantReferenceCode = $merchantReferenceCode;
		$request->deviceFingerprintID = $this->getDeviceFingerPrint();
		$request->customerID = $this->getMerchantCustomerId();
		$request->transactionKey = $this->getTransactionKey();
		$request->clientLibrary = $this->client::CLIENT_LIBRARY_VERSION;
		$request->clientLibraryVersion = phpversion();
		$request->clientEnvironment = php_uname();
		return $request;
	}

	protected function createCard()
	{
		/** @var \Omnipay\Common\CreditCard $creditCard */
		$creditCard = $this->getCard();

		$card = new \stdClass();
		$card->accountNumber = $creditCard->getNumber();
		$card->expirationMonth = $creditCard->getExpiryMonth();
		$card->expirationYear = $creditCard->getExpiryYear();

		if (!is_null($creditCard->getCvv())) {
			$card->cvNumber = $creditCard->getCvv();
		}

		if (!is_null($this->getCardType())) {
			$card->cardType = $this->getCardType();
		}

		return $card;
	}

	/**
	 * @return \stdClass
	 */
	protected function createBillingAddress()
	{
		$data = $this->getParameter('billTo');
		if (!empty($data)){
			return (object) $data;
		}

		/** @var \Omnipay\Common\CreditCard $creditCard */
		$creditCard = $this->getCard();
		$billTo = new \stdClass();
		if (!is_null($creditCard)) {
			$billTo->firstName = $creditCard->getBillingFirstName();
			$billTo->lastName = $creditCard->getBillingLastName();

			if (!is_null($creditCard->getBillingAddress1())) {
				$billTo->street1 = $creditCard->getBillingAddress1();
			}

			if (!is_null($creditCard->getBillingAddress2())) {
				$billTo->street2 = $creditCard->getBillingAddress2();
			}

			if (!is_null($creditCard->getBillingCity())) {
				$billTo->city = $creditCard->getBillingCity();
			}

			if (!is_null($creditCard->getBillingState())) {
				$billTo->state = $creditCard->getBillingState();
			}

			if (!is_null($creditCard->getBillingPostcode())) {
				$billTo->postalCode = $creditCard->getBillingPostcode();
			}

			if (!is_null($creditCard->getBillingCountry())) {
				$billTo->country = $creditCard->getBillingCountry();
			}

			if (!is_null($creditCard->getEmail())) {
				$billTo->email = $creditCard->getEmail();
			}

			if (!is_null($creditCard->getBillingPhone())) {
				$billTo->phoneNumber = $creditCard->getBillingPhone();
			}

			return $billTo;
		}

		return $billTo;
	}

	protected function createShippingAddress()
	{
		$data = $this->getParameter('shipTo');

		if (!empty($data)){
			return (object) $data;
		}

		/** @var \Omnipay\Common\CreditCard $creditCard */
		$creditCard = $this->getCard();
		$shipTo = new \stdClass();
		if (!is_null($creditCard)) {
			$shipTo->firstName = $creditCard->getBillingFirstName();
			$shipTo->lastName = $creditCard->getBillingLastName();

			if (!is_null($creditCard->getBillingAddress1())) {
				$shipTo->street1 = $creditCard->getBillingAddress1();
			}

			if (!is_null($creditCard->getBillingAddress2())) {
				$shipTo->street2 = $creditCard->getBillingAddress2();
			}

			if (!is_null($creditCard->getBillingCity())) {
				$shipTo->city = $creditCard->getBillingCity();
			}

			if (!is_null($creditCard->getBillingState())) {
				$shipTo->state = $creditCard->getBillingState();
			}

			if (!is_null($creditCard->getBillingPostcode())) {
				$shipTo->postalCode = $creditCard->getBillingPostcode();
			}

			if (!is_null($creditCard->getBillingCountry())) {
				$shipTo->country = $creditCard->getBillingCountry();
			}

			if (!is_null($creditCard->getEmail())) {
				$shipTo->email = $creditCard->getEmail();
			}

			if (!is_null($creditCard->getBillingPhone())) {
				$shipTo->phoneNumber = $creditCard->getBillingPhone();
			}

			return $shipTo;
		}

		return $shipTo;
	}

}
