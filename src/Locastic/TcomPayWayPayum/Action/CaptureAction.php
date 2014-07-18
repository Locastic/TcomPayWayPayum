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

use Locastic\TcomPayWay\Handlers\TcomPayWayPaymentProcessHandler;
use Locastic\TcomPayWay\Model\Card;
use Locastic\TcomPayWay\Model\Customer\Customer;
use Locastic\TcomPayWay\Model\Customer\CustomersClient;
use Locastic\TcomPayWay\Model\Payment;
use Locastic\TcomPayWay\Model\Shop;
use Locastic\TcomPayWay\Model\Transaction;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\CaptureRequest;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\ResponseInteractiveRequest;
use Payum\Core\Request\SecuredCaptureRequest;
use Payum\Core\Request\GetHttpQueryRequest;
use Payum\Core\Request\Http\GetRequestRequest;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class CaptureAction extends PaymentAwareAction implements ApiAwareInterface
{
    /**
     * @var TcomPayWayPaymentProcessHandler
     */
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof TcomPayWayPaymentProcessHandler) {
            throw new UnsupportedApiException('Expected instance of TcomPayWayPaymentProcessHandler object.');
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

        /*
         * secured3d response processing missing
         */

        $shop = new Shop();
        $customersClient = new CustomersClient($model['httpAccept'], $model['httpUserAgent'], $model['originIP']);
        $customer = new Customer($model['firstName'], $model['lastName'], $model['street'], $model['city'], $model['postalCode'], $model['country'], $model['email'], $model['phoneNumber'], $customersClient);
        $_card = $model['card']->get();
        $card = new Card($_card['number'], $_card['expiryMonth'], $_card['expiryYear'], $_card['cvd']);
        $payment = new Payment($model['shoppingCartId'], $model['amount'], $model['numOfInstallments'], $model['paymentMode']);

        $transaction = new Transaction($shop, $customer, $card, $payment);

        $response = $this->api->process($transaction);

        $model['paymentStatus'] = $response['status'];

        if ($response['status'] == 'secure3d') {
            $model['ASCUrl'] = $response['ASCUrl'];
            $model['PaReq'] = $response['PaReq'];
            $model['TermUrl'] = $request->getToken()->getTargetUrl();

            throw new ResponseInteractiveRequest($this->getSecure3dFormHtml(
                $model['ASCUrl'],
                $model['PaReq'],
                $model['TermUrl']
            ));
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

    /**
     * @param string $ascUrl
     * @param string $paReq
     * @param string $termUrl
     *
     * @return string
     */
    protected function getSecure3dFormHtml($ascUrl, $paReq, $termUrl)
    {
        return <<<HTML
<p>Vaša kartica sudjeluje u Secure3D programu.</p>
<form action="{$ascUrl}" method="POST">
    <input type="hidden" name="PaReq" value="{$paReq}"/>
    <input type="hidden" name="TermUrl" value="{$termUrl}"/>
    <input type="submit" value="Nastavi s kupovinom"/>
</form>
HTML;
    }
}