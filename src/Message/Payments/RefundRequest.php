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
        return 'SettledSuccessfully';
    }

	public function getData()
    {
        $this->validate('transactionReference');

	    $request = $this->createRequest($this->getTransactionId());
	    $ccCreditService = new stdClass();
	    $ccCreditService->run = 'true';
	    $ccCreditService->captureRequestID = $this->getTransactionReference();

        $request->ccAuthService = $ccCreditService;

        if ($card = $this->buildCard()) {
            $request->card = $card; //for stand-alone credits
        }

        if ($orderRequestToken = $this->getRequestToken()) {
            $request->orderRequestToken = $orderRequestToken;
        }

        if ($items = $this->buildOrderItems()){
            $request->item = $items;
        }

        if ($descriptor = $this->getDescription()){
            $request->invoiceHeader = (object)array(
                'merchantDescriptor'=>substr($descriptor, 0, 23)
            );
        }

        $request->billTo = $this->buildBillingAddress();
        $request->shipTo = $this->buildShippingAddress();

	    $purchaseTotals = new stdClass();
	    $purchaseTotals->grandTotalAmount = $this->getAmount();
	    $request->purchaseTotals = $purchaseTotals;

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
