<?php
namespace Locastic\TcomPayWayPayum\Action;

use Payum\Core\Action\PaymentAwareAction;

class CaptureAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
    }
}