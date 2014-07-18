<?php

/*
 * This file is part of the LocasticTcomPayWayPayum package.
 *
 * (c) locastic <https://github.com/locastic/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Locastic\TcomPayWay\Model;

/**
 * @author SNjegovan <sandro@locastic.com>
 */
class Shop
{
    private $_id;
    private $_username;
    private $_password;
    private $_secretKey;

//    function __construct($id, $username, $password, $secretKey)
    function __construct()
    {
        $this->_id = 10000006;
        $this->_username = "test3d";
        $this->_password = "test3d";
        $this->_secretKey = "test3d";
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getSecretKey()
    {
        return $this->_secretKey;
    }

}