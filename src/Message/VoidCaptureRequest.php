<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Void Capture Request
 */
class VoidCaptureRequest extends AbstractRequest
{
	/**
	 *
	 * @return CommonResponse
	 */
	public function getData()
    {
        $this->validate('transactionReference');

	    $request = $this->createRequest($this->getTransactionId());
	    $ccVoidService = new stdClass();
	    $ccVoidService->run = 'true';
	    $ccVoidService->voidRequestID  = $this->getTransactionReference();
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
