<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;


/**
 * Cybersource Delete Card Request
 */
class DeleteCardRequest extends AbstractRequest
{

	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());

        $request->recurringSubscriptionInfo = (object)[
            'subscriptionID'=>$this->getToken()
        ];

        $request->paySubscriptionDeleteService = (object)[
            'run'=>"true"
        ];

	    return $request;
    }
}
