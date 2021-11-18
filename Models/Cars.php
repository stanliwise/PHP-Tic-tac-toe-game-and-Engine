<?php
class Cars extends BaseModel{
    protected $brand;
    protected $model;
    protected $color;
    protected $Engine;
    protected $year;
    protected $mileage;
    protected $fuelType;

    public function ___construct(
        $brand,
        $model,
        $color,
        $Engine,
        $year,
        $mileage,
        $fuelType
    ){
        $this->brand = $brand;
        $this->model = $model; 
        $this->color = $color;
        $this->Engine = $Engine;
        $this->year = $year;
        $this->mileage = $mileage;
        $this->fuelType = $fuelType;
    }

    public static function postCar(){

    }

    public function viewCar(){

    }

    public static function searchCar(){
        
    }

    public static function AvailableCars(){

    }
}