<?php

abstract class BaseModel{
    public function __get($property_name){
        if(isset($this->$property_name))
            return $this->$property_name;
        
        return null;
    }
}