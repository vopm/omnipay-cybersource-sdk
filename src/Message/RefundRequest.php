<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Refund Request
 */
class RefundRequest extends AbstractRequest
{
	/**
	 *
	 * @return CommonResponse
	 */
	public function getData()
    {
        $this->validate('transactionReference');

	    $request = $this->createRequest($this->getTransactionId());
	    $ccAuthReversalService = new stdClass();
	    $ccAuthReversalService->run = 'true';
	    $ccAuthReversalService->authRequestID = $this->getTransactionReference();

		$request->ccAuthReversalService = $ccAuthReversalService;

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

        return $request;
    }
}
