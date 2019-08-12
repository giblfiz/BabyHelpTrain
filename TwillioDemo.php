<?php
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
require __DIR__ . '/config/secrets.php';

// Your Account SID and Auth Token from twilio.com/console
$account_sid = TWILLIO_ACCOUNT_SID;
$auth_token = TWILLIO_AUTH_TOKEN;
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+16108548401";
//(610) 854-8401

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+16102206245',
    array(
        'from' => $twilio_number,
        'body' => 'I sent this message in under 10 minutes!'
    )
);
