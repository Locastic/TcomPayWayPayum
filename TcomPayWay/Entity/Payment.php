<?php

namespace Locastic\Bundle\TcomPayWayBundle\Entity;

class Card
{
    private $id;
    private $number;
    private $expDate;

    function __construct($id, $number, $expDate)
    {
        $this->id = $id;
        $this->number = $number;
        $this->expDate = $expDate;
    }

    public function getExpDate()
    {
        return $this->expDate;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getId()
    {
        return $this->id;
    }

}