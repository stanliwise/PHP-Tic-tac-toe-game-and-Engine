<?php

class Payment extends BaseModel{
    protected $PaymentID;
    protected $BookID;
    protected $Amount;
    protected $PaymentType;

    public function construct(
        $PaymentID,
        $BookID,
        $Amount,
        $PaymentType
    ){
        $this->PaymentID = $PaymentID;
        $this->BookID = $BookID;
        $this->Amount = $Amount;
        $this->PaymentType = $PaymentType;
    }

    /**
     * insert payment into database
     */
    public function Payment(){

    }
}