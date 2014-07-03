<?php

namespace Locastic\TcomPayWay;

class Api {

    //configs
    private $payWaySoap;
    private $shopId;
    private $shopUsername;
    private $shopPassword;
    private $secretKey;

    public function __construct($shopId, $shopUsername, $shopPassword, $secretKey) {
        $this->shopId = $shopId;
        $this->shopUsername = $shopUsername;
        $this->shopPassword = $shopPassword;
        $this->secretKey = $secretKey;

        $this->setService();
    }

    /**
     *
     * @param string $cardNumber
     * @param decimal $amount
     */
    public function getValidInstallments($cardNumber, $amount)
    {
        $response = $this->payWaySoap
            ->GetValidInstallments(array(
                    'shopID' => $this->shopId,
                    'shopUsername' => $this->shopUsername,
                    'shopPassword' => $this->shopPassword,
                    'cardNumber' => $cardNumber,
                    'amount' => $amount))
            ->GetValidInstallmentsResult;

        if($response->ReturnCode > 0) {
            return $this->getErrorMessageResponse($response->ReturnCode);
        }

        return array(
            'validResponse' => true,
            'installments' => $response->Installments->int,
        );
    }

    /**
     *
     * @param string $firstName
     * @param string $lastNaem
     * @param string $street
     * @param string $city
     * @param string $postalCode
     * @param string $country
     * @param string $phoneNumber
     * @param string $email
     * @param string $cardId
     * @param string $cardNumber
     * @param string $cardExpirationDate
     * @param string $cardCVD
     * @param string $shoppingCartId
     * @param string $totalAmount
     * @param int $paymentMode
     * @param string $signature
     * @param string $secure3DPARes
     * @param int $numberOfInstallments
     * @param string $httpAccept
     * @param string $httpUserAgent
     * @param string $originIp
     */
    public function processAuthorizationExt($firstName, $lastName,
        $street, $city, $postalCode, $country, $phoneNumber, $email, $cardId, $cardNumber, $cardExpirationDate, $cardCVD, $shoppingCartId,
        $totalAmount, $paymentMode, $secure3DPARes, $numberOfInstallments, $httpAccept, $httpUserAgent, $originIp)
    {
        $response = $this->payWaySoap
            ->ProcessAuthorizationExt(array(
                    'shopID' => $this->shopId,
                    'shopUsername' => $this->shopUsername,
                    'shopPassword' => $this->shopPassword,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'street' => $street,
                    'city' => $city,
                    'country' => $country,
                    'postalCode' => $postalCode,
                    'phoneNumber' => $phoneNumber,
                    'email' => $email,
                    'cardID' => $cardId,
                    'cardNumber' => $cardNumber,
                    'cardExpirationDate' => $cardExpirationDate,
                    'cardCVD' => $cardCVD,
                    'shoppingCartId' => $shoppingCartId,
                    'totalAmount' => $totalAmount,
                    'paymentMode' => $paymentMode,
                    'signature' => $this->generateSignature($shoppingCartId, $totalAmount),
                    'secure3DPARes' => $secure3DPARes,
                    'numberOfInstallments' => $numberOfInstallments,
                    'httpAccept' => $httpAccept,
                    'httpUserAgent' => $httpUserAgent,
                    'originIp' => $originIp,
                ))
            ->ProcessAuthorizationExtResult;
        ;

        if($response->ReturnCode > 0) {
            if($response->Secure3DReturnCode != 0 && $response->Secure3DReturnCode != 3) {
                return $this->getErrorSecure3DMessageResponse($response->Secure3DReturnCode);
            }

            return $this->getErrorMessageResponse($response->ReturnCode);
        }

        return array(
            'validResponse' => true,
            'approvalCode' => $response->ApprovalCode,
            'transactionId' => $response->TransactionID,
        );
    }

    /**
     *
     * @param int $transactionId
     * @param decimal $amount
     * @param string $signature
     * @param string $originIp
     */
    public function processSettlementEx($transactionId, $amount, $originIp)
    {
        $response = $this->payWaySoap
            ->ProcessSettlement(array(
                    'shopId' => $this->shopId,
                    'shopUsername' => $this->shopUsername,
                    'shopPassword' => $this->shopPassword,
                    'transactionId' => $transactionId,
                    'amount' => $amount,
                    'signature' => $this->generateSignature($transactionId, $amount),
                    'originIp' => $originIp,
                ));

        return $response;
    }

    /**
     *
     * @param decimal $amount
     * @param string $cardId
     * @param string $cardNumber
     * @param string $cardExpirationDate
     * @param string $numberOfInstallments
     * @param string $httpAccept
     * @param string $httpUserAgent
     * @param string $orginIp
     */
    public function sendSecure3DRequestExtn($amount, $cardId, $cardNumber, $cardExpirationDate, $numberOfInstallments, $httpAccept, $httpUserAgent, $orginIp)
    {
        $response = $this->payWaySoap
            ->SendSecure3DRequestExtn(array(
                    'shopID' => $this->shopId,
                    'shopUsername' => $this->shopUsername,
                    'shopPassword' => $this->shopPassword,
                    'amount' => $amount,
                    'cardID' => $cardId,
                    'cardNumber' => $cardNumber,
                    'cardExpirationDate' => $cardExpirationDate,
                    'numberOfInstallments' => $numberOfInstallments,
                    'httpAccept' => $httpAccept,
                    'httpUserAgent' => $httpUserAgent,
                    'orginIp' => $orginIp,
                ))
            ->SendSecure3DRequestExtnResult;

        if($response->ReturnCode > 0) {
            return $this->getErrorMessageResponse($response->ReturnCode);
        }

        if($response->Secure3DReturnCode == 1) {
            return array(
                'validResponse' => true,
                'secure3DReturnCode' => $response->Secure3DReturnCode,
                'ASCUrl' => $response->ASCUrl,
                'PaReq' => $response->PaReq,
            );
        }
        elseif ($response->Secure3DReturnCode == 3 || $response->Secure3DReturnCode == 4) {
            return array(
                'validResponse' => true,
                'secure3DReturnCode' => $response->Secure3DReturnCode,
            );
        }

        return $this-getErrorSecure3DMessageResponse($response->Secure3DReturnCode);
    }

    // private methods

    private function setService()
    {
        $this->payWaySoap = new \SoapClient("https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx?wsdl", array('location' => "https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx",
                'trace' => 1,
                'uri' => 'https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx'
            ));

    }

    private function generateSignature($shoppingCartId, $totalAmount)
    {
        return md5($this->shopId . $this->secretKey . $shoppingCartId . $this->secretKey . number_format($totalAmount/100, 2, ',', '') . $this->secretKey);
    }

    private function getErrorMessageResponse($returnCode)
    {
        return array(
            'validResponse' => false,
            'messageError' => ReturnCode::getReturnCode($returnCode),
        );
    }

    private function getErrorSecure3DMessageResponse($returnSecure3DCode)
    {
        return array(
            'secure3DReturnCode' => $returnSecure3DCode,
            'validResponse' => false,
            'messageError' => ReturnCode::getSecure3DreturnCode($returnSecure3DCode),
        );
    }

}