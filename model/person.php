<?php
require "tableClass.php";

class Person extends tableClass{ 
    public  $firstName;
    public  $lastName;
    public  $createdOn;
    public  $email;
    public  $phone;
    public  $rowid;
    
   static function getById($id){
    $values = DB::DB()->query("SELECT * FROM person WHERE rowid = $rowid")->fetch();
    $p = new Person();
    print_r($values);
 
   }
   

    
    function __construct(){
     
    }
    
 }
 