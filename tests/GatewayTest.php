<?php

namespace Omnipay\Cybersource;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{

	/**
	 * @var Gateway
	 */
    public $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setTestMode(true);

	    if (getenv('CYBERSOURCE_MERCHANT')) $gateway->setMerchantId(getenv('CYBERSOURCE_MERCHANT'));
	    if (getenv('CYBERSOURCE_TRANSACTION_KEY')) $gateway->setTransactionKey(getenv('CYBERSOURCE_TRANSACTION_KEY'));
    }


	public function atestAuthorize(){
		$creditCard = $this->getValidCard();
		$purchaseOptions = array(
			'amount' => '12.00',
			'currency' => 'USD',
			'card' => $creditCard,
			'merchantReferenceCode' => uniqid()
		);

		/** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
		$request = $this->gateway->authorize($purchaseOptions);

		/** @var \Omnipay\Cybersource\Message\CommonResponse $response */
		$response = $request->send();
		$this->assertEquals(true, $response->isPending(), $response->getResponseMessage());
		$this->assertNotEmpty($response->getAuthReconciliationId());

	}

	public function testCapture(){
		$creditCard      = $this->getValidCard();
		$uniqid          = uniqid();
		$purchaseOptions = array(
			'amount' => '12.00',
			'card' => $creditCard,
			'merchantReferenceCode' => $uniqid
		);

		/** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
		$request = $this->gateway->authorize($purchaseOptions);

		/** @var \Omnipay\Cybersource\Message\CommonResponse $response */
		$response = $request->send();

		$purchaseOptions = array(
			'amount' => '12.00',
			'currency' => 'usd',
			'merchantReferenceCode' => $uniqid,
			'authRequestID' => $response->getAuthReconciliationId(),
		);

		/** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
		$request = $this->gateway->capture($purchaseOptions);

		/** @var \Omnipay\Cybersource\Message\CommonResponse $response */
		$response = $request->send();
		$this->assertEquals(true, $response->isSuccessful(), $response->getRequestId() . '===>' . $response->getResponseMessage());

	}

	public function getValidCard()
	{
		return array(
			'firstName' => 'Example',
			'lastName' => 'User',
			'number' => '4111111111111111',
			'expiryMonth' => rand(1, 12),
			'expiryYear' => gmdate('Y') + rand(1, 5),
			'cvv' => rand(100, 999),
			'billingAddress1' => '123 Billing St',
			'billingAddress2' => 'Billsville',
			'billingCity' => 'Billstown',
			'billingPostcode' => '12345',
			'billingState' => 'CA',
			'billingCountry' => 'US',
			'billingPhone' => '(555) 123-4567',
			'shippingAddress1' => '123 Shipping St',
			'shippingAddress2' => 'Shipsville',
			'shippingCity' => 'Shipstown',
			'shippingPostcode' => '54321',
			'shippingState' => 'NY',
			'shippingCountry' => 'US',
			'shippingPhone' => '(555) 987-6543',
			'email' => 'test@me.com',
		);
	}
}
