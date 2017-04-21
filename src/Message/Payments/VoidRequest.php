<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Void Request
 */
class VoidRequest extends AbstractRequest
{

    const VOID_TYPE_SALE = 'sale';
    const VOID_TYPE_CAPTURE = 'capture';
    const VOID_TYPE_REFUND = 'refund';
    const VOID_TYPE_CREDIT = 'credit';
    const VOID_TYPE_AUTHORIZATION = 'authorization';

    public function getSuccessStatus(){
        return 'Voided';
    }


	public function getData()
    {
        $request = $this->createRequest($this->getTransactionId());


        $voidType = $this->getVoidType();

        if ($voidType == self::VOID_TYPE_AUTHORIZATION){
            $this->validate('transactionReference');

            $request = $this->createRequest($this->getTransactionId());
            $ccAuthReversalService = new stdClass();
            $ccAuthReversalService->run = 'true';
            $ccAuthReversalService->authRequestID = $this->getTransactionReference();

            $request->ccAuthReversalService = $ccAuthReversalService;

            $purchaseTotals = new stdClass();
            $purchaseTotals->grandTotalAmount = $this->getAmount();
            $request->purchaseTotals = $purchaseTotals;
        }else{
            $this->validate('transactionReference');

            $ccVoidService = new stdClass();
            $ccVoidService->run = 'true';
            $ccVoidService->voidRequestID = $this->getTransactionReference();
//            $ccVoidService->voidRequestToken  = $this->getRequestToken();

            $request->voidService = $ccVoidService;
        }

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

    public function getVoidType(){
        return $this->getParameter('voidType');
    }

    public function setVoidType($value){
        return $this->setParameter('voidType', $value);
    }
}
