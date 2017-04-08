<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Capture Request
 */
class AuthorizeRequest extends AbstractRequest
{

	public function sendData($data)
    {
        $client = $this->client;
	    $request = $this->createRequest($this->getMerchantReferenceCode());

		$ccAuthService = new stdClass();
		$ccAuthService->run = 'true';
		$request->ccAuthService = $ccAuthService;

	    $request->card = $this->createCard();
	    $request->billTo = $this->createBillingAddress();
	    $request->shipTo = $this->createBillingAddress();

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->currency = $this->getCurrency();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

	    $reply = $client->runTransaction($request);

	    return new CommonResponse($this, $reply);
    }


	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 */
	public function getData() {
		return [];
	}
}
