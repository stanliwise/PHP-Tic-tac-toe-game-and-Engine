<?php

class Customers extends BaseModel{
    protected $CustomerID;
    protected $CustomerName;
    protected $CustomerMobile;
    protected $CustomerEmail;
    protected $CustomerPassword;

    public function __construct(
        $CustomerID,
        $CustomerName,
        $CustomerMobile,
        $CustomerEmail,
        $CustomerPassword
    )
    {
        $this->CustomerID = $CustomerID;
        $this->CustomerName = $CustomerName;
        $this->CustomerMobile = $CustomerMobile;
        $this->CustomerEmail = $CustomerEmail;
        $this->Password = $CustomerPassword;
    }

    public static function getCustomerById(){

    }

    public static function getCustomerByEmail(){

    }

    public function register(){

    }

    public function login(){

    }

    public function viewCars(){

    }

    public function viewPayments(){

    }

    public function viewTrip(){

    }

    public function searchCars(){

    }
}