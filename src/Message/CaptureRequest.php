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
        $this->validate('transactionReference');

	    $request = $this->createRequest($this->getTransactionId());

	    $ccCaptureService = new stdClass();
	    $ccCaptureService->run = 'true';
	    $ccCaptureService->authRequestID = $this->getTransactionReference();

		$request->ccCaptureService = $ccCaptureService;

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

        return $request;
    }
}
