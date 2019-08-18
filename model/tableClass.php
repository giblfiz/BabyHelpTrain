<?php

/**
 *If it's just going to be a class
 *that mostly wraps around a table, we can do this!
 *
 *I'm taking the super lazy 
 **/
abstract class tableClass {
    __call($method, $params){
      $var = lcfirst(substr($method, 3));

     if (strncasecmp($method, "get", 3) === 0) {
         return $this->$var;
     }
     if (strncasecmp($method, "set", 3) === 0) {
         $this->$var = $params[0];
     }
    }
}
