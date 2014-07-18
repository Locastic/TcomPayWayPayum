<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWay\Clients;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
interface TcomPayWayClientInterface
{
    public function SendSecure3DRequestExtn($shopId, $shopUsername, $shopPassword, $amount, $cardId, $cardNumber, $cardExpDate, $numOfInstallments, $httpAccept, $httpUserAgent, $orginIp);

    public function ProcessAuthorizationExt($shopId, $shopUsername, $shopPassword, $firstName, $lastName, $street, $city, $postalCode, $country, $phoneNumber, $email, $cardId, $cardNumber, $cardExpirationDate, $cardCVD, $shoppingCartId, $totalAmount, $paymentMode, $signature, $secure3DPARes, $numberOfInstallments, $httpAccept, $httpUserAgent, $originIp);

    public function ProcessReversalEx($shopId, $shopUsername, $shopPassword, $transactionId, $signature, $originIp);

    public function ProcessSettlementEx($shopId, $shopUsername, $shopPassword, $transactionId, $amount, $signature, $originIp);

    public function GetValidInstallments($shopId, $shopUsername, $shopPassword, $cardNumber, $amount);
}