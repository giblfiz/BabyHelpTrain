<?php
define(BHT_TWILLIO_NUMBER, '+16108548401');
define(DEV_PHONE_NUMBER, '+16102206245');



/**
 *Below are the TYPEs for the options table
 *This tells us what they are doing. Maybe they should be strings
 *instead of Ints to simplify
 */
define(SCHEDULE_A_TIME, '1');
 

function devlog($value){
    file_put_contents("developer_log.txt",
        $value . "\n",
        FILE_APPEND);
    }