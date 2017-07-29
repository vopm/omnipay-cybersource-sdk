<?php

namespace Omnipay\CyberSourceSoap\Message\Responses;


/**
 * Class AfsReply
 * @package Omnipay\CyberSourceSoap\Responses
 *
 * @property int reasonCode
 * @property int afsResult
 * @property int hostSeverity
 * @property string consumerLocalTime
 * @property string afsFactorCode
 * @property string addressInfoCode
 * @property string internetInfoCode
 * @property string suspiciousInfoCode
 * @property string velocityInfoCode
 * @property string scoreModelUsed
 * @property string deviceFingerprint
 */
class AfsReply
{
    /**
     * @var mixed
     */
    protected $data;

    public static function build($data)
    {
        if ($data && isset($data['afsReply'])){
            $instance = new self();
            $instance->data = $data['afsReply'];

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

    public function __get($name)
    {
        return isset($this->data[$name])? $this->data[$name]: null;
    }
}