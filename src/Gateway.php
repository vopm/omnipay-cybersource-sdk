<?php

namespace Omnipay\Cybersource;

use Omnipay\Common\AbstractGateway;

/**
 * Cybersource - Soap Gateway
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface capture( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface purchase( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface completePurchase( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface refund( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface void( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface createCard( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface updateCard( array $options = array() )
 * @method \Omnipay\Common\Message\RequestInterface deleteCard( array $options = array() )
 */
class Gateway extends AbstractGateway
{
    /* Default Abstract Gateway methods that need to be overridden */
    public function getName()
    {
        return 'Cybersource - SDK';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'transactionKey' => '',
            'username' => '',
            'password' => '',
        );
    }

    /**
     * @param string $merchantId
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchantId', $merchantId);
        return $this;
    }

    /**
     * return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->setParameter('username', $username);
        return $this;
    }

    /**
     * return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->setParameter('password', $password);
        return $this;
    }

    /**
     * return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param string $transactionKey
     * @return $this
     */
    public function setTransactionKey($transactionKey)
    {
        $this->setParameter('transactionKey', $transactionKey);
        return $this;
    }

    /**
     * return string
     */
    public function getTransactionKey()
    {
        return $this->getParameter('transactionKey');
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\AuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\CaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\CaptureRequest
     */
    public function voidCapture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\VoidCaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\VoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\VoidRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\VoidRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\CreateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\RetrieveCardRequest
     */
    public function retrieveCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\RetrieveCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\UpdateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\UpdateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\DeleteCardRequest
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\DeleteCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Cybersource\Message\CreateCardFromPaymentRequest
     */
    public function tokenize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Cybersource\Message\CreateCardFromPaymentRequest', $parameters);
    }
}
