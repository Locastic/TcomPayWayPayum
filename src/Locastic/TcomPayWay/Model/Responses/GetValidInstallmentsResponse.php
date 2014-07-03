<?php

namespace Locastic\TcomPayWay\Model\Reponses;

class TransactionResponse
{
    private $_returnCode;
    private $_approvalCode;
    private $_transactionId;
    private $_secure3dReturnCode;
    private $_secure3dErrorMessage;

    function __construct($_returnCode, $_approvalCode, $_transactionId, $_secure3dReturnCode, $_secure3dErrorMessage)
    {
        $this->_approvalCode = $_approvalCode;
        $this->_returnCode = $_returnCode;
        $this->_secure3dErrorMessage = $_secure3dErrorMessage;
        $this->_secure3dReturnCode = $_secure3dReturnCode;
        $this->_transactionId = $_transactionId;
    }

    public function getApprovalCode()
    {
        return $this->_approvalCode;
    }

    public function getReturnCode()
    {
        return $this->_returnCode;
    }

    public function getSecure3dErrorMessage()
    {
        return $this->_secure3dErrorMessage;
    }

    public function getSecure3dReturnCode()
    {
        return $this->_secure3dReturnCode;
    }

    public function getTransactionId()
    {
        return $this->_transactionId;
    }

}