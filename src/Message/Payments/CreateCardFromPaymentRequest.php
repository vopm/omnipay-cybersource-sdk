<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;

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
