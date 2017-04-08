<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Capture Request
 */
class CaptureRequest extends AbstractRequest
{

	/**
	 * @param mixed $data
	 *
	 * @return CommonResponse
	 */
	public function sendData($data)
    {
        $client = $this->client;
	    $request = $this->createRequest($this->getMerchantReferenceCode());

	    $ccCaptureService = new stdClass();
	    $ccCaptureService->run = 'true';
	    $ccCaptureService->authRequestID = $request->authRequestID = $this->getAuthRequestId();

		$request->ccCaptureService = $ccCaptureService;

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

	/**
	 * @param string $request
	 *
	 * @return $this
	 */
	public function setAuthRequestId($request)
	{
		$this->setParameter('authRequestId', $request);
		return $this;
	}

	/**
	 * return string
	 */
	public function getAuthRequestId()
	{
		return $this->getParameter('authRequestId');
	}
}
