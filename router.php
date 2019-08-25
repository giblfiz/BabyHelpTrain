<?php
//Router!!!
require_once "vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;
use Twilio\Rest\Client;

require __DIR__ . '/config/secrets.php';
require __DIR__ . '/config/config.php';
require "database/database.php";


//hopefully the autoloaders will have kicked in by now. 
session_start(); // Initialize the session


//Auto include any class that is called
spl_autoload_register(function ($class_name){
    $class_name = strtolower($class_name);
    if(file_exists(__dir__ . "/model/$class_name.php")){
        require __dir__ . "/model/$class_name.php";
    } else {
        require __dir__ . "/$class_name.php";
    }
});
 
 
function getCurrentUser(){
    if(isset($_SESSION['Guardian'])){
        return $_SESSION['Guardian']; 
    }
}
 
 

//Load the file in question

require __dir__ . "/controler/" . $_SERVER["SCRIPT_NAME"] . ".php";

//if(!isset($_SESSION["loggedin"])){
//    header("location: login");
//    exit;
//}

