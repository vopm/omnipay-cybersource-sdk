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
	 *
	 * @return CommonResponse
	 */
	public function getData()
    {
	    $request = $this->createRequest($this->getMerchantReferenceCode());

	    $ccCaptureService = new stdClass();
	    $ccCaptureService->run = 'true';
	    $ccCaptureService->authRequestID = $request->authRequestID = $this->getAuthRequestId();

		$request->ccCaptureService = $ccCaptureService;

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

        return $request;
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
