<?php

namespace Omnipay\CyberSourceSoap\Message\Responses;

/**
 * Class AuthorizationReply
 * @package Omnipay\CyberSourceSoap\Responses
 *
 * @property string reasonCode
 * @property float amount
 * @property string authorizationCode
 * @property string avsCode
 * @property string avsCodeRaw
 * @property string authorizedDateTime
 * @property string processorResponse
 * @property string reconciliationID
 * @property string merchantAdviceCode
 * @property string merchantAdviceCodeRaw
 * @property string cavvResponseCode
 * @property string cavvResponseCodeRaw
 * @property string paymentNetworkTransactionID
 * @property string ownerMerchantID
 *
 */
class AuthorizationReply
{
    /**
     * @var mixed
     */
    protected $data;

    public static function build($data)
    {
        if ($data && isset($data['ccAuthReply'])){
            $instance = new AuthorizationReply();
            $instance->data = $data['ccAuthReply'];

            return $instance;
        }

        return false;
    }

    public function isSuccessful(){
        return in_array($this->getReasonCode(), array(100, 110));
    }

    public function getReasonCode(){
        return @$this->data['reasonCode'];
    }

    public function getAuthorizationCode(){
        return @$this->data['authorizationCode'];
    }

    public function getReconciliationID(){
        return @$this->data['authorizationCode'];
    }

    public function __get($name)
    {
        return isset($this->data[$name])? $this->data[$name]: null;
    }
}