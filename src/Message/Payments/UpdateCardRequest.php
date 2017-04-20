<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;

/**
 * Cybersource Update Card Request
 */
class UpdateCardRequest extends AbstractRequest
{

	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());

        $request->recurringSubscriptionInfo = (object)[
            'subscriptionID'=>$this->getToken()
        ];

        $request->paySubscriptionUpdateService = (object)[
            'run'=>"true"
        ];

        $request->card = $this->createCard();
        $request->billTo = $this->createBillingAddress();
        $request->shipTo = $this->createShippingAddress();

	    return $request;
    }
}
