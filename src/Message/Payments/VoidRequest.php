<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Void Request
 */
class VoidRequest extends AbstractRequest
{
    public function getSuccessStatus(){
        return 'Voided';
    }


	public function getData()
    {
        $this->validate('transactionReference');

	    $request = $this->createRequest($this->getTransactionId());

	    $ccVoidService = new stdClass();
	    $ccVoidService->run = 'true';
	    $ccVoidService->voidRequestID = $this->getTransactionReference();
        $ccVoidService->voidRequestToken  = $this->getRequestToken();

		$request->voidService = $ccVoidService;

        return $request;
    }

    public function getRequestToken()
    {
        return $this->getParameter('requestToken');
    }

    public function setRequestToken($value)
    {
        $this->setParameter('requestToken', $value);
    }
}
