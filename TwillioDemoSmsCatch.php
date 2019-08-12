<?php

//usage notes
// php -S localhost:8000 # from this directory runs a tiny webserver
// ngrok http 8000 opens that server out to the wide world 

require_once "vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;

// Set the content-type to XML to send back TwiML from the PHP Helper Library
header("content-type: text/xml");

$response = new MessagingResponse();
$response->message(
    "I'm using the Twilio PHP library to respond to this SMS, For Newborn help train organizing"
);

echo $response;
