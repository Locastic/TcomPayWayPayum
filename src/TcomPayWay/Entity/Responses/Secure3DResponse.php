<?php

namespace Locastic\TcomPayWay\Entity\Customer;

class Customer
{
    private $_firstName;
    private $_lastName;
    private $_street;
    private $_city;
    private $_postalCode;
    private $_country;
    private $_phoneNumber;
    private $_email;

    /**
     * @var \Locastic\Bundle\TcomPayWayBundle\Entity\Customer\CustomersClient
     */
    private $_client;

    function __construct($firstName, $lastName, $street, $city, $postalCode, $country, $email, $phoneNumber, $client)
    {
        $this->_city = $city;
        $this->_country = $country;
        $this->_email = $email;
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_phoneNumber = $phoneNumber;
        $this->_postalCode = $postalCode;
        $this->_street = $street;
        $this->_client = $client;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function getPhoneNumber()
    {
        return $this->_phoneNumber;
    }

    public function getPostalCode()
    {
        return $this->_postalCode;
    }

    public function getStreet()
    {
        return $this->_street;
    }

    /**
     * @return \Locastic\Bundle\TcomPayWayBundle\Entity\Customer\CustomersClient
     */
    public function getClient()
    {
        return $this->_client;
    }

}