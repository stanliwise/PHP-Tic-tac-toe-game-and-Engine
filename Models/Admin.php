<?php
    class Admin extends BaseModel{
        protected $email;
        protected $username;
        protected $password;

        public function __construct(
            $email,
            $username,
            $password
        ) {
            $this->email = $email;
            $this->username = $username;
            $this->password = $password;
        }

        public function register(){

        }

        public function login(){

        }

        public function postCar(){

        }

        public function viewCars(){

        }
        
        public function editCars(){

        }

        public function postDrivers(){

        }

        public function editDrivers(){

        }

        public function viewDrivers(){
            
        }

        public function viewPayments(){

        }

        public function viewTrip(){
            
        }

        protected function requiredFields(): array
        {   
            return ['email', 'username', 'password'];
        }
    }