<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWayPayum\Bridge\Sylius;

use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\CaptureRequest;
use Payum\Core\Request\ResponseInteractiveRequest;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Payum\Core\Request\Http\RedirectUrlInteractiveRequest;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\PayumBundle\Payum\Request\ObtainCreditCardRequest;
use Payum\Core\Security\SensitiveValue;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class CapturePaymentAction extends PaymentAwareAction
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param EngineInterface $templating
     * @param string $templateName
     * @param Request $request
     */
    public function __construct(EngineInterface $templating, $templateName, $request)
    {
        $this->templating = $templating;
        $this->templateName = $templateName;
        $this->request = $request;
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

        /** @var $payment PaymentInterface */
        $payment = $request->getModel();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $details = $payment->getDetails();

        if (empty($details)) {
            $this->payment->execute($obtainCreditCardRequest = new ObtainCreditCardRequest($order));
            $creditCard = $obtainCreditCardRequest->getCreditCard();

            $details = array();
            $details['firstName'] = $order->getBillingAddress()->getFirstName();
            $details['lastName'] = $order->getBillingAddress()->getLastName();
            $details['email'] = $order->getUser()->getEmail();
            $details['street'] = $order->getBillingAddress()->getStreet();
            $details['city'] = $order->getBillingAddress()->getCity();
            $details['postalCode'] = $order->getBillingAddress()->getPostcode();
            $details['country'] = $order->getBillingAddress()->getCountry()->getName();
            $details['phoneNumber'] = ''; // ?: 'Unknown'
            $details['amount'] = 2350; //$order->getTotal();

            $details['card'] = new SensitiveValue(array(
                'number'      => $creditCard->getNumber(),
                'expiryMonth' => $creditCard->getExpiryMonth(),
                'expiryYear'  => $creditCard->getExpiryYear(),
                'cvd'         => $creditCard->getSecurityCode()
            ));

            $details['numOfInstallments'] =  1;

            $details['shoppingCartId'] =  $order->getNumber() . "-" . rand();
            $details['paymentMode'] =  1;

            $details['httpAccept'] = $this->request->headers->get('Accept');
            $details['httpUserAgent'] = $this->request->headers->get('User-Agent');
            $details['originIP'] = $this->request->getClientIp();

            $payment->setDetails($details);
        }

        $details = ArrayObject::ensureArrayObject($details);

        try {
            $request->setModel($details);
            $this->payment->execute($request);

            $payment->setDetails((array) $details);
            $request->setModel($payment);
        } catch (ResponseInteractiveRequest $interactiveRequest) {
            $payment->setDetails((array) $details);
            $request->setModel($payment);

            throw $interactiveRequest;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CaptureRequest &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
