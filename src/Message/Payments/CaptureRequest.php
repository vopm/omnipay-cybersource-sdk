<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use Omnipay\CyberSourceSoap\Message\CommonResponse;
use stdClass;

/**
 * Cybersource Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getSuccessStatus(){
        return 'SettledSuccessfully';
    }

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

        if ($descriptor = $this->getDescription()){
            $request->invoiceHeader = (object)array(
                'merchantDescriptor'=>substr($descriptor, 0, 23)
            );
        }

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

        return $request;
    }
}
