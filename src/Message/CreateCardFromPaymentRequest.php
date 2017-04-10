<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Create Card From Payment Request
 */
class CreateCardFromPaymentRequest extends AbstractRequest
{

	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());

        $request->recurringSubscriptionInfo = (object)[
            'frequency'=>'on-demand'
        ];

        $request->paySubscriptionCreateService = (object)[
            'run'=>"true",
            'paymentRequestID'=>$this->getTransactionReference()
        ];

	    return $request;
    }
}
