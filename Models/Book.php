<?php 
class Book extends BaseModel{
    protected $BookID;
    protected $CustomerID;
    protected $DriverID;
    protected $Amount;
    protected $StartTime;
    protected $EndTime;
    protected $Picklocation;
    protected $DropLocation;

    public function __construct(
        $BookID,
        $CustomerID,
        $DriverID,
        $Amount,
        $StartTime,
        $EndTime,
        $Picklocation,
        $DropLocation
    ) {
        $this->BookID = $BookID;
        $this->CustomerID = $CustomerID;
        $this->DriverID = $DriverID;
        $this->Amount = $Amount;
        $this->StartTime = $StartTime;
        $this->EndTime = $EndTime;
        $this->Picklocation = $Picklocation;
        $this->DropLocation = $DropLocation;
    }

    public function Booking(){

    }

}