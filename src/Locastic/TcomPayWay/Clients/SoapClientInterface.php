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
interface SoapClientInterface
{
    public function initClient($wsdlUrl, $options);
}