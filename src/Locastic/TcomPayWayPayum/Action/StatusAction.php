<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWayPayum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\StatusRequestInterface;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
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

        if ('success' == $model['paymentStatus']) {
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