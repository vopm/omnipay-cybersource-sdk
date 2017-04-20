<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;

/**
 * Cybersource Retrieve Card Request
 */
class RetrieveCardRequest extends AbstractRequest
{

	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());

        $request->recurringSubscriptionInfo = (object)[
            'subscriptionID'=>$this->getToken()
        ];

        $request->paySubscriptionRetrieveService = (object)[
            'run'=>"true"
        ];

	    return $request;
    }
}
