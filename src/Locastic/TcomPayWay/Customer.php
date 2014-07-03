<?php

namespace Locastic\TcomPayWay;

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
    private $_httpAccept;
    private $_httpUserAgent;
    private $_originIP;

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->_city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->_country = $country;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getHttpAccept()
    {
        return $this->_httpAccept;
    }

    /**
     * @param mixed $httpAccept
     */
    public function setHttpAccept($httpAccept)
    {
        $this->_httpAccept = $httpAccept;
    }

    /**
     * @return mixed
     */
    public function getHttpUserAgent()
    {
        return $this->_httpUserAgent;
    }

    /**
     * @param mixed $httpUserAgent
     */
    public function setHttpUserAgent($httpUserAgent)
    {
        $this->_httpUserAgent = $httpUserAgent;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getOriginIP()
    {
        return $this->_originIP;
    }

    /**
     * @param mixed $originIP
     */
    public function setOriginIP($originIP)
    {
        $this->_originIP = $originIP;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->_phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->_phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->_postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->_postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->_street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->_street = $street;
    }
}