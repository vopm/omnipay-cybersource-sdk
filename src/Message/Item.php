<?php

namespace Omnipay\CyberSourceSoap\Message;


class Item extends \Omnipay\Common\Item
{
    public function getSku()
    {
        return $this->getParameter('sku');
    }

    public function setSku($value)
    {
        return $this->setParameter('sku', $value);
    }

    public function getCode()
    {
        return $this->getParameter('code');
    }

    public function setCode($value)
    {
        return $this->setParameter('code', $value);
    }


    /**
     * @return float get unit cost of item
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set unit cost of item
     * @param $value
     *
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }
}