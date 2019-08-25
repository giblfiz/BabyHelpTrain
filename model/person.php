<?php
require_once "tableClass.php";

class Person extends tableClass{ 

    function getMaxId(){
        $r = DB::DB()->query("SELECT MAX(rowid) from person");
        return $r->fetch()['MAX(rowid)'];
    }


    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM person WHERE rowid = $rowid")->fetch();
    $p = new Person($values, $rowid);
    return $p;
   }
   
   static function getByEmail($email){
    $values = DB::DB()->query("SELECT *, rowid FROM person WHERE email = '$email'")->fetch();
    $p = new Person($values, $values["rowid"]);
    return $p;
   }

    
   function __construct($values, $id){
     $this->values = $values;
     $this->rowid = $id;
     $this->tableName = "person";
   }
        
 }
 