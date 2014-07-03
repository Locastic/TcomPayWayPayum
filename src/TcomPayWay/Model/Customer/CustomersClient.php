<?php

namespace Locastic\TcomPayWay\Model;

class CustomersClient
{
    private $_httpAccept;
    private $_httpUserAgent;
    private $_originIP;

    function __construct($_httpAccept, $_httpUserAgent, $_originIP)
    {
        $this->_httpAccept = $_httpAccept;
        $this->_httpUserAgent = $_httpUserAgent;
        $this->_originIP = $_originIP;
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