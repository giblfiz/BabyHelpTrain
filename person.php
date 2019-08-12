<?php
 class Person {
    public $name;
    public $email;
    public $phone;
    
    function __construct($name, $email, $phone){
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
    }
 }
 