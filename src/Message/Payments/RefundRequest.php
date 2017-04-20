<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getSuccessStatus(){
        return 'PendingSettlement';
    }

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
