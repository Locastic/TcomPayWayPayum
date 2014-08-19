<?php

namespace Locastic\TcomPayWayPayum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\StatusRequestInterface;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        $model = $request->getModel();

        if (false == isset($model['paymentStatus'])) {
            $request->markNew();

            return;
        }

        if ('success' == $model['paymentStatus'] or 'finished' == $model['paymentStatus']) {
            $request->markSuccess();

            return;
        }

        if ('secure3d' == $model['paymentStatus']) {
            $request->markPending();

            return;
        }

        if ('error' == $model['paymentStatus']) {
            $request->markFailed();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof StatusRequestInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}