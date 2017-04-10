<?php

namespace Omnipay\Cybersource\Message;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

/**
 * Cybersource Capture Request
 */
class PurchaseRequest extends AuthorizeRequest
{

	public function getData()
    {
	    $request = parent::getData();

        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;

	    return $request;
    }
}
