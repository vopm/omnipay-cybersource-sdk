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

	public function getData()
    {
	    $request = $this->createRequest($this->getTransactionId());

		$ccAuthService = new stdClass();
		$ccAuthService->run = 'true';
		$request->ccAuthService = $ccAuthService;

		if ($this->getToken()){
            $request->recurringSubscriptionInfo = (object)[
                'subscriptionID'=>$this->getToken()
            ];
        }else{
            $request->card = $this->createCard();
        }

	    $request->billTo = $this->createBillingAddress();
	    $request->shipTo = $this->createShippingAddress();

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->currency = $this->getCurrency();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

	    return $request;
    }
}
