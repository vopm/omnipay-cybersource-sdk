<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

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
