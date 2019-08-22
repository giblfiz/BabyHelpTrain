<?php
// Database is a singleton class.
class DB {
  private static $instance = NULL;   // Hold the class instance.
  private $pdo = NULL;
  public $debug_level = 0; // 0 none, 1: errors, 2: commands, 3: results
  
  private function __construct(){
    $this->pdo = new PDO('sqlite:' . __DIR__ . '/babyHelpTrain.db');
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // The expensive process (e.g.,db connection) goes here.
    }
 
  // The object is created from within the class itself
  // only if the class has no instance.
  public static function DB(){
    if (self::$instance == NULL){
      self::$instance = new DB();
    }
    return self::$instance;
  }

  /**
   *This is a wrapper that lets us build in debugging
   *as well as error catching, and insertion attack defense
   */
  public function exec($command){
    if($this->debug_level >= 2){
      devlog($command);
    }
    try{
      $this->pdo->exec($command);
      if($this->debug_level >= 2){
        devlog("Inserted rowid = " .$this->pdo->lastInsertId());
      }
      return $this->pdo->lastInsertId(); // in case we did an insert

    } catch (exception $e){
        if($this->debug_level){
          devlog("EXCEPTION: " .  $e->getMessage());
        }
    }
  }
  
  public function query($command){
    if($this->debug_level >= 2){
      devlog($command);
    }
    try{
      return $this->pdo->query($command);
    } catch (exception $e){
        if($this->debug_level){
            devlog("EXCEPTION: " .  $e->getMessage());
       }
    }
    
  }
  
  public function getMaxId($table){
    $r = DB::DB()->query("SELECT MAX(rowid) FROM " .$table );
print_r($r);

    return $r->fetch()['MAX(rowid)'];

  }
   
  public function updateSentSms(){
    
  }
  
  public function getLastSms($responder_phone_number){
        return $this->query("SELECT max(sent_sms.rowid) FROM person LEFT JOIN
                 sent_sms ON (person.rowid = sent_sms.person_row_id) 
                 WHERE person.phone = '$responder_phone_number' ");
  }
  
  public function getOptionsFromSms($sent_sms_rowid){
    return $this->query("SELECT * FROM option 
                 WHERE sent_row_id = '$sent_sms_rowid' ");    
  }
  
}
 


