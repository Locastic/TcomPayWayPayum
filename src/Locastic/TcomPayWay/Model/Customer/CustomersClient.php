<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWay\Model\Customer;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class CustomersClient
{
    private $_httpAccept;
    private $_httpUserAgent;
    private $_originIP;

    function __construct($httpAccept, $httpUserAgent, $originIP)
    {
        $this->_httpAccept = $httpAccept;
        $this->_httpUserAgent = $httpUserAgent;
        $this->_originIP = $originIP;
    }

    public function getHttpAccept()
    {
        return $this->_httpAccept;
    }

    public function getHttpUserAgent()
    {
        return $this->_httpUserAgent;
    }

    public function getOriginIP()
    {
        return $this->_originIP;
    }

}