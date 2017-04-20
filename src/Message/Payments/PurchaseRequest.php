<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use stdClass;

/**
 * Cybersource Capture Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    public function getSuccessStatus(){
        return 'SettledSuccessfully';
    }

	public function getData()
    {
	    $request = parent::getData();

        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;

	    return $request;
    }
}
