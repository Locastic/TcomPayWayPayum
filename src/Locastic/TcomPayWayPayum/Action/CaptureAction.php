<?php

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
use Payum\Core\Request\SecuredCapture;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\RenderTemplate;
use Payum\Core\Reply\HttpResponse;


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
        /** @var $request SecuredCapture */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $shop = new Shop();
        $customersClient = new CustomersClient($model['httpAccept'], $model['httpUserAgent'], $model['originIP']);

        $customer = new Customer(
            $model['firstName'],
            $model['lastName'],
            $model['address'],
            $model['city'],
            $model['zipCode'],
            $model['country'],
            $model['email'],
            $model['phoneNumber'],
            $customersClient
        );

        $card = new Card(
            $model['card_number'],
            $model['card_expiration_date']['date'],
            $model['card_cvd']
        );

        $payment = new Payment(
            $model['shoppingCartId'],
            $model['amount'],
            $model['numOfInstallments'],
            $model['paymentMode']
        );

        $transaction = new Transaction($shop, $customer, $card, $payment);

        if (isset($_POST['PaRes'])) {
            $transaction->setSecure3dpares($_POST['PaRes']);
        }

        $response = $this->api->process($transaction);

        $model['paymentStatus'] = $response['status'];

        if ($response['status'] == 'secure3d') {
            $model['ASCUrl'] = $response['ASCUrl'];
            $model['PaReq'] = $response['PaReq'];
            $model['TermUrl'] = $request->getToken()->getTargetUrl();

            $secure3dTmpl = new RenderTemplate(
                'LocasticTcomPaywayPayumBundle:TcomPayWay:secure3d.html.twig', array(
                    'ASCUrl' => $response['ASCUrl'],
                    'PaReq' => $response['PaReq'],
                    'TermUrl' => $request->getToken()->getTargetUrl(),
                )
            );

            $this->payment->execute($secure3dTmpl);
            throw new HttpResponse($secure3dTmpl->getResult());
        }
        else {
            $model['tcomData'] = $response['authResponse'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof SecuredCapture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}