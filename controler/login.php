<?php


if (isset($_REQUEST['password']) && isset($_REQUEST["email"])){
//It's a Login!!
    $g = Guardian::getByEmail($_REQUEST['email']);    
    if(password_verify($_REQUEST['password'], $g->getPasshash())){
        $_REQUEST['Guardian'] = $g;
        echo "You have been logged in";
    } else {
        throw new exception("bad password I think");        
    }
} else if(isset($_REQUEST['new_password']) && isset($_REQUEST["new_password2"]) && isset($_REQUEST["new_email"]) ){
    // It's a signup!!
    
} else {
    //It's just visiting the page, give them the login & signup form 
}