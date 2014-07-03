<?php

namespace Locastic\TcomPayWay\Model;

class Card
{
    private $id;
    private $number;
    private $expDate;
    private $cvd;

    function __construct($id, $number, $expDate, $cvd = null)
    {
        $this->id = $id;
        $this->number = $number;
        $this->expDate = $expDate;
        $this->cvd = $cvd;
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

    public function setCvd($cvd)
    {
        $this->cvd = $cvd;
    }

    public function getCvd()
    {
        return $this->cvd;
    }

}