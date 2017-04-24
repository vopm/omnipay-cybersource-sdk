<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Create Card Request
 */
class CreateCardRequest extends AbstractRequest
{

	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());

        $request->recurringSubscriptionInfo = (object)[
            'frequency'=>'on-demand'
        ];

        $request->paySubscriptionCreateService = (object)[
            'run'=>"true"
        ];

        $request->card = $this->buildCard();
        $request->billTo = $this->buildBillingAddress();
        $request->shipTo = $this->buildShippingAddress();

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
}
