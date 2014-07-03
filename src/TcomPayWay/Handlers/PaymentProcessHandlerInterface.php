<?php

namespace Locastic\TcomPayWay\Handler;


interface PaymentProcessHandlerInterface
{
    public function SendSecure3DRequestExtn();

    public function ProcessAuthorizationExt();

    public function ProcessReversalEx();

    public function ProcessSettlementEx();

    public function GetValidInstallments();
}