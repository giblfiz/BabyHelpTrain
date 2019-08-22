<?php
require_once  "tableClass.php";

class Option extends tableClass{
   const tableName = "option";

    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM option WHERE rowid = $rowid")->fetch();
    $p = new Option($values, $id);
    return $p;
   }
   

     public function putOption($sms_id, $code, $date, $type){
    //format(DateTime::RFC2822)
    $this->exec("INSERT INTO option (content, code, created_on, type_row_id, sent_row_id) VALUES ( '". $date->format(DateTime::RFC2822) ."' ,". $code .",". time() .",". $type .",". $sms_id ." )");
   }

    
    function __construct($values){
     $this->values = $values;
     $this->rowid = $id;
    }
    
 }
 