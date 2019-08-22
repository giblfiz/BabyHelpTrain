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
         return($this); // let us use cascades!
     }
    }
    
    function write(){
        if (!$this->dirty){
            throw new Exception("This object never had any values set, not writing");
        }


        //clear out all of the 1, 2, 3 replication from db loads        
        foreach($this->values as $k => $v){
            if(is_numeric($k)){
                unset($this->values[$k]);
            }
        }
        
        
        if(!is_numeric($this->rowid)){
            $columns = join(", ", array_keys($this->values));
            $entrys = "'" . join("', '", ($this->values)) . "'";

            $this->rowid = DB::DB()->exec("INSERT INTO $this->tableName ($columns) VALUES ($entrys)");
        } else {
            $stringParts = array();;
            foreach ($this->values as $k => $v){
                $stringParts[] = "$k = '$v'";
            }
            $string = join(", ", $stringParts);

            DB::DB()->exec("UPDATE $this->tableName SET $string WHERE rowid =" . $this->rowid);        
        }
    }
    
}
