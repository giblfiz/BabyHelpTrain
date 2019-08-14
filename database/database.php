<?php
// Database is a singleton class.
class DB {
  private static $instance = NULL;   // Hold the class instance.
  private $myPDO = NULL;
  
  private function __construct()
  {
    $this->myPDO = new PDO('sqlite:' . __DIR__ . '/babyHelpTrain.db');
    // The expensive process (e.g.,db connection) goes here.
  }
 
  // The object is created from within the class itself
  // only if the class has no instance.
  public static function getDB()
  {
    if (self::$instance == NULL)
    {
      self::$instance = new DB();
    }
 
    return self::$instance;
  }
}
 


