<?php

namespace Locastic\TcomPayWay\Model;

class Shop
{
    private $id;
    private $username;
    private $password;

    function __construct($id, $password, $username)
    {
        $this->id = $id;
        $this->password = $password;
        $this->username = $username;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

}