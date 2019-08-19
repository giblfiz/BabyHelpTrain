<?php
require_once  "tableClass.php";

class Option extends tableClass{
   const tableName = "option";

    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM option WHERE rowid = $rowid")->fetch();
    $p = new Option($values, $id);
    return $p;
   }
   

    
    function __construct($values){
     $this->values = $values;
     $this->rowid = $id;
    }
    
 }
 