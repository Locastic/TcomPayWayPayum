<?php

namespace Locastic\TcomPayWay\Model\Reponses;

class Secure3DResponse
{
    private $_returnCode;
    private $_secure3dReturnCode;
    private $_ascUrl;
    private $_paReq;

    function __construct($_returnCode, $_secure3dReturnCode, $_ascUrl, $_paReq)
    {
        $this->_ascUrl = $_ascUrl;
        $this->_paReq = $_paReq;
        $this->_returnCode = $_returnCode;
        $this->_secure3dReturnCode = $_secure3dReturnCode;
    }

    public function getAscUrl()
    {
        return $this->_ascUrl;
    }

    public function getPaReq()
    {
        return $this->_paReq;
    }

    public function getReturnCode()
    {
        return $this->_returnCode;
    }

    public function getSecure3dReturnCode()
    {
        return $this->_secure3dReturnCode;
    }

}