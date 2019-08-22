<?php
require_once "tableClass.php";

class Person extends tableClass{ 

    function getMaxId(){
      echo "ff";      
        $r = DB::DB()->query("SELECT MAX(rowid) from person");
         echo "ff";
        print_r($r);
//        return $r->fetch()['MAX(rowid)'];
    }

    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM person WHERE rowid = $rowid")->fetch();
    $p = new Person($values, $rowid);
    return $p;
   }
   

    
   function __construct($values, $id){
     $this->values = $values;
     $this->rowid = $id;
     $this->tableName = "person";
   }
        
 }
 