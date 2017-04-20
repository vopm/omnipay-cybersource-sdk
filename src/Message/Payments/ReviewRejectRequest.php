<?php

namespace Omnipay\CyberSourceSoap\Message\Payments;

use Omnipay\CyberSourceSoap\Message\AbstractRequest;
use stdClass;

/**
 * Cybersource Review Reject Request
 */
class ReviewRejectRequest extends AbstractRequest
{
    public function getSuccessStatus(){
        return 'PendingCapture';
    }

	public function getData()
    {
        $this->validate('transactionId', 'transactionReference');

	    $request = $this->createRequest($this->getTransactionId());

		$request->caseManagementActionService = (object)array(
		    'run'=>'true',
            'actionCode'=>'ACCEPT',
            'requestID'=>$this->getTransactionReference(),
            'comments'=>$this->getComment(),
        );

	    return $request;
    }

    public function getComment()
    {
        return ($comment = $this->getParameter('comment'))?$comment: 'Order accepted';
    }

    public function setComment($value){
        $this->setParameter('comment', $value);
    }
}
