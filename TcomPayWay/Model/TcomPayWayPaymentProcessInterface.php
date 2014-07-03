<?php

namespace Locastic\Bundle\TcomPayWayBundle\Model;

interface TcomPayWayClientInterface
{
    public function SendSecure3DRequestExtn();

    public function ProcessAuthorizationExt();

    public function ProcessReversalEx();

    public function ProcessSettlementEx();
}