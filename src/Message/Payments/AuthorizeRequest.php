<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Capture Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getSuccessStatus(){
        return 'PendingCapture';
    }


	public function getData()
    {
	    $request = $this->createRequest($this->getTransactionId());

		$ccAuthService = new stdClass();
		$ccAuthService->run = 'true';
		$request->ccAuthService = $ccAuthService;

		if ($this->getToken()){
            $request->recurringSubscriptionInfo = (object)[
                'subscriptionID'=>$this->getToken()
            ];
        }else{
            $request->card = $this->buildCard();
        }

	    $request->billTo = $this->buildBillingAddress();
	    $request->shipTo = $this->buildShippingAddress();

        if ($items = $this->buildOrderItems()){
            $request->item = $items;
        }

        if ($linkToRequest = $this->getLinkToRequest()){
            $request->linkToRequest = $linkToRequest;
        }

        if ($descriptor = $this->getDescription()){
            $request->invoiceHeader = (object)array(
                'merchantDescriptor'=>substr($descriptor, 0, 23)
            );
        }

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->currency = $this->getCurrency();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

	    return $request;
    }

    public function getLinkToRequest()
    {
        return $this->getParameter('linkToRequest');
    }

    public function setLinkToRequest($value)
    {
        $this->setParameter('linkToRequest', $value);
    }
}
