<?php

namespace Omnipay\Cybersource;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{

	/**
	 * @var Gateway
	 */
    public $gateway;
    protected $paymentToken;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = $gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setTestMode(true);

	    if (getenv('CYBERSOURCE_MERCHANT')) $gateway->setMerchantId(getenv('CYBERSOURCE_MERCHANT'));
	    if (getenv('CYBERSOURCE_TRANSACTION_KEY')) $gateway->setTransactionKey(getenv('CYBERSOURCE_TRANSACTION_KEY'));
    }


	public function testAuthorize(){
		$creditCard = $this->getValidCard();
		$purchaseOptions = array(
			'amount' => '12.00',
			'currency' => 'USD',
			'card' => $creditCard,
			'transactionId' => 'c3ed6419-b55c-4d79-bbe4-14d21edf27bd'
		);

		/** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
		$request = $this->gateway->authorize($purchaseOptions);

		/** @var \Omnipay\Cybersource\Message\CommonResponse $response */
		$response = $request->send();
		$this->assertEquals(true, $response->isPending(), $response->getResponseMessage());
		$this->assertNotEmpty($response->getAuthReconciliationId());

	}

    public function testPurchase(){

        $purchaseOptions = array(
            'amount' => '12.00',
            'currency' => 'USD',
            'merchantCustomerId' => '12345',
            'deviceFingerPrint' => '4917176323706201603009',
            'card' => $this->getValidCard(),
            'transactionId' => 'DO1234567891'
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();

        $this->assertEquals(true, $response->isPending(), $response->getResponseMessage());
        $this->assertNotEmpty($response->getAuthReconciliationId());

    }

    public function testVoidCapture(){
        $purchaseOptions = array(
            'transactionReference' => '4917176323706201603009',
            'transactionId' => 'DO1234567890',
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->voidCapture($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();

        $this->assertTrue($response->isSuccessful(), $response->getMessage());

    }

    public function testCapture(){
        $purchaseOptions = array(
            'amount' => '12.00',
            'transactionReference' => '4917176323706201603009',
            'deviceFingerPrint' => '4917176323706201603009',
            'transactionId' => 'DO1234567890',
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->capture($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();


        $this->assertTrue($response->isSuccessful(), $response->getMessage());

    }

    public function testCreateCard(){

        $purchaseOptions = array(
            'amount' => 0.00,
            'currency' => 'USD',
            'merchantCustomerId' => '12345',
            'deviceFingerPrint' => '4917176323706201603009',
            'card' => $this->getValidCard(),
            'transactionId' => 'DO1234567891'
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->createCard($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();

        $this->assertEquals(true, $response->isSuccessful(), $response->getResponseMessage());
        $this->assertNotEmpty($response->getTransactionReference()); //token

        $this->paymentToken = $response->getTransactionReference();

    }

    public function testTokenAuthorization(){

        $purchaseOptions = array(
            'amount' => 10.00,
            'currency' => 'USD',
            'deviceFingerPrint' => '2342566235234',
            'token'=>$this->paymentToken,
            'transactionId' => 'US23492834944'
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->authorize($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();

        $this->assertEquals(true, $response->isSuccessful(), $response->getResponseMessage());
    }

    public function testDeleteCard(){

        $purchaseOptions = array(
            'token'=>$this->paymentToken,
            'transactionId' => 'US23492834944'
        );

        /** @var \Omnipay\Cybersource\Message\PurchaseRequest $request */
        $request = $this->gateway->deleteCard($purchaseOptions);

        /** @var \Omnipay\Cybersource\Message\CommonResponse $response */
        $response = $request->send();

        $this->assertEquals(true, $response->isSuccessful(), $response->getResponseMessage());
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
