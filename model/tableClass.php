<?php

/**
 *If it's just going to be a class
 *that mostly wraps around a table, we can do this!
 *
 *I'm taking the super lazy 
 **/
abstract class tableClass {
    protected $rowid;
    protected $dirty;
    protected $values;
    protected $tableName;
        
    function setId($id){
        $this->rowid = $id;
    }
    
    function getId(){
        return $this->rowid;
    }
    
    function __call($method, $params){
      $var = lcfirst(substr($method, 3));

     if (strncasecmp($method, "get", 3) === 0) {
         return $this->values[$var];
     }
     if (strncasecmp($method, "set", 3) === 0) {
         $this->values[$var] = $params[0];
         $this->dirty = true;
     }
    }
    
}
