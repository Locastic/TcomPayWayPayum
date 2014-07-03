<?php

namespace Locastic\TcomPayWay\Handler;

use Locastic\TcomPayWay\Entity\Shop;
use Locastic\TcomPayWay\Model\TcomPayWayClientInterface;

class TcomPayWayClient implements TcomPayWayClientInterface
{
    private $shop = null;

    private $client;

    public function __construct($shopId, $shopUsername, $shopPassword, $shopSecretKey)
    {
        $this->shop = new Shop();
        $this->shop->setId($shopId);
        $this->shop->setUsername($shopUsername);
        $this->shop->setPassword($shopPassword);
        $this->shop->setUsername($shopSecretKey);

        $this->client = $this->getSoapClient();
    }

    public function SendSecure3DRequestExtn()
    {
        // TODO: Implement SendSecure3DRequestExtn() method.
    }

    public function ProcessAuthorizationExt()
    {
        // TODO: Implement ProcessAuthorizationExt() method.
    }

    public function ProcessReversalEx()
    {
        // TODO: Implement ProcessReversalEx() method.
    }

    public function ProcessSettlementEx()
    {
        // TODO: Implement ProcessSettlementEx() method.
    }

    public function GetValidInstallments()
    {
        // TODO: Implement GetValidInstallments method.
    }

    private function getSoapClient()
    {
        return new \SoapClient(
            "https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx?wsdl", array(
                'location' => "https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx",
                'trace' => 1,
                'uri' => 'https://pgw.t-com.hr/MerchantPayment/PaymentWS.asmx'
            )
        );

    }

    private function generateSignature($shoppingCartId, $totalAmount)
    {
        return md5(
            $this->shop->getId() . $this->shop->getSecretKey() . $shoppingCartId . $this->shop->getSecretKey() . number_format(
                $totalAmount / 100,
                2,
                ',',
                ''
            ) . $this->shop->getSecretKey()
        );
    }
}