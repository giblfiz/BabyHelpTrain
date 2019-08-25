<?php
require_once "tableClass.php";

class Guardian extends tableClass{ 
    public $person;
    public $gcal;

    function getMaxId(){
        $r = DB::DB()->query("SELECT MAX(rowid) from person");
        return $r->fetch()['MAX(rowid)'];
    }

    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM guardian WHERE rowid = $rowid")->fetch();
    $p = new Guardian($values, $rowid);
    return $p;
   }

   static function getByPersonId($rowid){
    $values = DB::DB()->query("SELECT * FROM guardian WHERE person_row_id = $rowid")->fetch();
    $p = new Guardian($values, $rowid);
    return $p;
   }


    static function getByEmail($email){
        $person = Person::getByEmail($email);
        $guard = Guardian::getByPersonId($person->rowid);
        $guard->person = $person;
        return $guard;
    }
   

    
   function __construct($values, $id){
     $this->values = $values;
     $this->rowid = $id;
     $this->tableName = "guardian";
   }
        
 }
 