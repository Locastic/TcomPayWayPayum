<?php
namespace Locastic\TcomPayWayPayum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\StatusRequestInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Bridge\Spl\ArrayObject;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request StatusRequestInterface */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $rawModel = (array)ArrayObject::ensureArrayObject($request->getModel());

        if (false == isset($rawModel['transaction']['processing']['result'])) {
            if (isset($rawModel['transaction']['token'])) {
                $request->markPending();
            } else {
                $request->markNew();
            }

            return;
        }

        if ($rawModel['transaction']['processing']['result'] == 'ACK') {
            $request->markSuccess();

            return;
        }

        if ($rawModel['transaction']['processing']['result'] != 'WAITING FOR SHOPPER') {
            $request->markPending();

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