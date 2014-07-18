<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWay\Handlers;

use Locastic\TcomPayWay\Clients\TcomPayWayClient;
use Locastic\TcomPayWay\Helpers\ErrorCodeInterpreter;
use Locastic\TcomPayWay\Model\Requests\Secure3DRequest;
use Locastic\TcomPayWay\Model\Transaction;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class TcomPayWayPaymentProcessHandler
{
    private $payWayClient;
    private $wsdlUrl;
    private $options;

    public function __construct($wsdl, $optionsLocation, $optionsTrace, $optionsUri)
    {
        $this->payWayClient = new TcomPayWayClient();

        $this->wsdlUrl = $wsdl;
        $this->options = array(
            'location'  => $optionsLocation,
            'trace'     => $optionsTrace,
            'uri'       => $optionsUri
        );
    }

    public function process(Transaction $transaction)
    {
        // init client

        $this->init($this->wsdlUrl, $this->options);

        // secure3d call

        $secure3DRequest = new Secure3DRequest(
            $transaction->getShop(),
            $transaction->getPayment(),
            $transaction->getCard(),
            $transaction->getCustomer()->getClient()
        );

        $secure3dResponse = $this->secure3dCall($secure3DRequest);

        if ($secure3dResponse['responseStatus']) {
            if ($secure3dResponse['secure3DReturnCode'] == 1) {
                // interactive request
                return array_merge(array('status' => 'secure3d'), $secure3dResponse);
            } else {
                // auth payment
                $authResponse = $this->authorize($transaction);

                $success = 'error';

                if ($authResponse['responseStatus']) {
                    $success = 'success';
                }

                return array_merge(array('status' => $success), $authResponse);
            }
        }

        return array_merge(array('status' => 'error'), $secure3dResponse);
    }

    protected function init($wsdlUrl, $options)
    {
        $this->payWayClient->initClient($wsdlUrl, $options);
    }

    protected function secure3dCall(Secure3DRequest $secure3DRequest)
    {
        $secure3dResponse = $this->payWayClient->SendSecure3DRequestExtn(
            $secure3DRequest->getShop()->getId(),
            $secure3DRequest->getShop()->getUsername(),
            $secure3DRequest->getShop()->getPassword(),
            $secure3DRequest->getPayment()->getAmount(),
            $secure3DRequest->getCard()->getId(),
            $secure3DRequest->getCard()->getNumber(),
            $secure3DRequest->getCard()->getExpDate(),
            $secure3DRequest->getPayment()->getNumOfInstallments(),
            $secure3DRequest->getCustomersClient()->getHttpAccept(),
            $secure3DRequest->getCustomersClient()->getHttpUserAgent(),
            $secure3DRequest->getCustomersClient()->getOriginIP()
        );

        if ($secure3dResponse->ReturnCode == 0) {
            if ($secure3dResponse->Secure3DReturnCode == 1) {
                return array(
                    'responseStatus'        => true,
                    'secure3DReturnCode'    => $secure3dResponse->Secure3DReturnCode,
                    'ASCUrl'                => $secure3dResponse->ASCUrl,
                    'PaReq'                 => $secure3dResponse->PaReq,
                );
            } elseif ($secure3dResponse->Secure3DReturnCode == 3 || $secure3dResponse->Secure3DReturnCode == 4) {
                return array(
                    'responseStatus'        => true,
                    'secure3DReturnCode'    => $secure3dResponse->Secure3DReturnCode,
                );
            }

            return array(
                'responseStatus'    => false,
                'message'           => $this->getErrorSecure3DMessageResponse($secure3dResponse->Secure3DReturnCode),
            );
        }

        return array(
            'responseStatus'    => false,
            'message'           => $this->getErrorMessageResponse($secure3dResponse->ReturnCode),
        );
    }

    protected function authorize(Transaction $transaction)
    {
        $authResponse = $this->payWayClient->ProcessAuthorizationExt(
            $transaction->getShop()->getId(),
            $transaction->getShop()->getUsername(),
            $transaction->getShop()->getPassword(),
            $transaction->getCustomer()->getFirstName(),
            $transaction->getCustomer()->getLastName(),
            $transaction->getCustomer()->getStreet(),
            $transaction->getCustomer()->getCity(),
            $transaction->getCustomer()->getPostalCode(),
            $transaction->getCustomer()->getCountry(),
            $transaction->getCustomer()->getPhoneNumber(),
            $transaction->getCustomer()->getEmail(),
            $transaction->getCard()->getId(),
            $transaction->getCard()->getNumber(),
            $transaction->getCard()->getExpDate(),
            $transaction->getCard()->getCvd(),
            $transaction->getPayment()->getShoppingCartId(),
            $transaction->getPayment()->getAmount(),
            $transaction->getPayment()->getMode(),
            $transaction->getSignature(),
            $transaction->getSecure3dpares(),
            $transaction->getPayment()->getNumOfInstallments(),
            $transaction->getCustomer()->getClient()->getHttpAccept(),
            $transaction->getCustomer()->getClient()->getHttpUserAgent(),
            $transaction->getCustomer()->getClient()->getOriginIP()
        );

        $responseStatus = true;

        if ($authResponse->ReturnCode > 0) {
            $responseStatus = false;
            $authResponse->ReturnCodeMessage = $this->getErrorMessageResponse($authResponse->ReturnCode);
        }

        return array(
            'responseStatus'    => $responseStatus,
            'authResponse'      => $authResponse,
        );
    }

    protected function settle(Transaction $transaction)
    {
        return $this->payWayClient->ProcessSettlementEx(
            $transaction->getShop()->getId(),
            $transaction->getShop()->getUsername(),
            $transaction->getShop()->getPassword(),
            $transaction->getId(),
            $transaction->getPayment()->getAmount(),
            $transaction->getSignature(),
            $transaction->getCustomer()->getClient()->getOriginIP()
        );
    }

    protected function getValidInstallments(Transaction $transaction)
    {
        $response = $this->payWayClient->GetValidInstallments(
            $transaction->getShop()->getId(),
            $transaction->getShop()->getUsername(),
            $transaction->getShop()->getPassword(),
            $transaction->getCard()->getNumber(),
            $transaction->getPayment()->getAmount()
        );

        if($response->ReturnCode > 0) {
            return $this->getErrorMessageResponse($response->ReturnCode);
        }

        return array(
            'responseStatus'    => true,
            'installments'      => $response->Installments->int,
        );
    }

    private function getErrorMessageResponse($returnCode)
    {
        return ErrorCodeInterpreter::getReturnCode($returnCode);
    }

    private function getErrorSecure3DMessageResponse($returnSecure3DCode)
    {
        return array(
            'secure3DReturnCode'    => $returnSecure3DCode,
            'responseStatus'        => false,
            'messageError'          => ErrorCodeInterpreter::getSecure3DreturnCode($returnSecure3DCode),
        );
    }
}