<?php
namespace Locastic\TcomPayWayPayum\Action;

use Locastic\TcomPayWay\Api;
use Locastic\TcomPayWay\Customer;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\CaptureRequest;


class CaptureAction extends PaymentAwareAction implements ApiAwareInterface
{
    /**
     * var TcomPayWayPaymentProccessInterface
     */
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Expected instance of TcomPayWay Api object.');
        }

        $this->api = $api;
    }


    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request CaptureRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $model['token']) {
            $customer = new Customer();

            $customer->setFirstName($model['NAME.GIVEN']);
            $customer->setLastName($model['NAME.FAMILY']);
            $customer->setStreet($model['ADDRESS.STREET']);
            $customer->setCity($model['ADDRESS.CITY']);
            $customer->setCountry($model['ADDRESS.COUNTRY']);
            $customer->setEmail($model['CONTACT.EMAIL']);



        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CaptureRequest &&
            $request->getModel() instanceof \ArrayAccess;
    }
}