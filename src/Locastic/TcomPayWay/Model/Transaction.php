<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWay\Model;

use Locastic\TcomPayWay\Helpers\TransactionsHelper;
use Locastic\TcomPayWay\Model\Customer\Customer;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class Transaction
{
    /**
     * @var \Locastic\TcomPayWay\Model\Shop
     */
    private $_shop;

    /**
     * @var \Locastic\TcomPayWay\Model\Customer\Customer
     */
    private $_customer;

    /**
     * @var \Locastic\TcomPayWay\Model\Payment
     */
    private $_payment;

    /**
     * @var \Locastic\TcomPayWay\Model\Card
     */
    private $_card;

    private $_signature;

    private $_secure3dpares;

    private $_transactionId;

    function __construct(Shop $shop, Customer $customer, Card $card, Payment $payment)
    {
        $this->_customer = $customer;
        $this->_payment = $payment;
        $this->_shop = $shop;
        $this->_card = $card;
        $this->_secure3dpares;
        $this->_signature = TransactionsHelper::signTransaction($this);
    }

    /**
     * @return \Locastic\TcomPayWay\Model\Customer\Customer
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * @return \Locastic\TcomPayWay\Model\Payment
     */
    public function getPayment()
    {
        return $this->_payment;
    }

    /**
     * @return \Locastic\TcomPayWay\Model\Shop
     */
    public function getShop()
    {
        return $this->_shop;
    }

    /**
     * @return \Locastic\TcomPayWay\Model\Card
     */
    public function getCard()
    {
        return $this->_card;
    }

    public function getSignature()
    {
        return $this->_signature;
    }

    public function setId($id)
    {
        $this->_transactionId = $id;
    }

    public function getId()
    {
        return $this->_transactionId;
    }

    public function setSecure3dpares($secure3dpares)
    {
        $this->_secure3dpares = $secure3dpares;
    }

    public function getSecure3dpares()
    {
        return ($this->_secure3dpares == NULL) ? '' : $this->_secure3dpares;
    }

}