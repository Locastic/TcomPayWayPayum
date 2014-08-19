<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWayPayum;

use Payum\Core\Payment;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
abstract class PaymentFactory
{
    /**
     * @return \Payum\Core\Payment
     */
    public static function create()
    {
        $payment = new Payment;

        return $payment;
    }

    /**
     */
    private function __construct()
    {
    }
}