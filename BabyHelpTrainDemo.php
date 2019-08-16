<?php
#Ok, this is the zero-attempt to knit gcal & twillio together
#
# This one does the pitching, most of the code will be in the catcher

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
require __DIR__ . '/config/secrets.php';
require __DIR__ . '/config/config.php';

//inhouse code
require __DIR__ . '/database/database.php';



function promptHelpOptions(){
    $calendar = null;
    $potentalDates = getPotentalDates($calendar);
    $sms_id = recordDateOptions($potentalDates);
    promptDateOptions($potentalDates, $sms_id);
}

function getPotentalDates($calendar){
    // At the moment, we are just throwing some random crap there
    $dates = array();
    $now = new DateTime();
    $dates[1] = clone $now->add(new DateInterval('P'.rand(1,3).'D'));
    $dates[2] = clone $now->add(new DateInterval('P'.rand(1,3).'D'));
    $dates[3] = clone $now->add(new DateInterval('P'.rand(1,3).'D'));

    return($dates);
}

function recordDateOptions($dates){
    $type = SCHEDULE_A_TIME;
    $db = DB::getDB();
    $sms_id = $db->stubSentSms(1);
    foreach ($dates as $key => $date){       
        $db->putOption($sms_id, $key, $date, $type); //
    }
    return $sms_id;
}

function promptDateOptions($dates, $sms_id){
    // Your Account SID and Auth Token from twilio.com/console
    $account_sid = TWILLIO_ACCOUNT_SID;
    $auth_token = TWILLIO_AUTH_TOKEN;
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
    
    // A Twilio number you own with SMS capabilities
    $twilio_number = BHT_TWILLIO_NUMBER;
        
    $client = new Client($account_sid, $auth_token);

    $body = "Are you avalible to schedule a visit with the new baby on:" ."\n";
    $body .= '(#1): ' . $dates[1]->format(DateTime::RFC2822) . "\n";
    $body .= '(#2): ' . $dates[2]->format(DateTime::RFC2822) . "\n";
    $body .= '(#3): ' . $dates[3]->format(DateTime::RFC2822) . "\n";
    $body .= "(#4): None of these times works for me \n";
    $body .= 'please respond with a number';
 
    $client->messages->create(
        // Where to send a text message (your cell phone?)
        DEV_PHONE_NUMBER,
        array(
            'from' => $twilio_number,
            'body' => $body
        )
    );    
}

DB::getDB()->debug_level = 2;
promptHelpOptions();