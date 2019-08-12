<?php
#Ok, this is the zero-attempt to knit gcal & twillio together
#
# This one does the pitching, most of the code will be in the catcher

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
require __DIR__ . '/config/secrets.php';
require __DIR__ . '/config/config.php';

// Your Account SID and Auth Token from twilio.com/console
$account_sid = TWILLIO_ACCOUNT_SID;
$auth_token = TWILLIO_AUTH_TOKEN;
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = BHT_TWILLIO_NUMBER;
echo $twilio_number;
//(610) 854-8401

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    DEV_PHONE_NUMBER,
    array(
        'from' => $twilio_number,
        'body' => 'Would you like to schedule a visit with the new baby?'
    )
);
