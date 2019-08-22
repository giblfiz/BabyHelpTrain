
<?php
require_once  "tableClass.php";

class SentSms extends tableClass{
 
    
   static function getById($rowid){
    $values = DB::DB()->query("SELECT * FROM sent_sms WHERE rowid = $rowid")->fetch();
    $p = new SentSms($values, $id);
    $p->rowid = $rowid;
    return $p;
   }
       
    function __construct($values = NULL, $id = NULL){
     $this->values = $values;
     $this->rowid = $id;
     $this->tableName = "sent_sms";
    }
    
   public function stub($person_id){
      $id = DB::DB()->exec("INSERT INTO sent_sms (created_on, person_row_id) VALUES (". time() . ", " . $person_id .")");
      devlog("Last insert ID was $id");
      return $id;

     }


 }
 


 