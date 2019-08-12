<?php

//usage notes
// php -S localhost:8000 # from this directory runs a tiny webserver
// ngrok http 8000 opens that server out to the wide world 

require_once "vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;

require __DIR__ . '/config/secrets.php';
require __DIR__ . '/config/config.php';

require __DIR__ . '/person.php'; //define a person


//Ok, lets add this to the users google clander

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('config/gcalCredentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'config/gcalToken.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
                throw new Exception("Problems with access token (gcal)");
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function addEvent($service, $calendarId, Person $who, DateTime $when){
    
    $event = new Google_Service_Calendar_Event(array(
      'summary' => $who->name . ' visits the home',
      'location' => '800 Howard St., San Francisco, CA 94103',
      'description' => 'Helping with Baby Care',
      'start' => array(
//        'dateTime' => '2019-08-12T09:00:00-07:00',
        'dateTime' => $when->format(DateTime::RFC3339),
        'timeZone' => 'America/Los_Angeles',
      ),
      'end' => array(
        'dateTime' => $when->add(new DateInterval('PT4H'))->format(DateTime::RFC3339),
        'timeZone' => 'America/Los_Angeles',
      ),
      //'recurrence' => array(
      //  'RRULE:FREQ=DAILY;COUNT=2'
      //),
      'attendees' => array(
        array('email' => $who->email),
      ),
      'reminders' => array(
        'useDefault' => FALSE,
        'overrides' => array(
          array('method' => 'email', 'minutes' => 24 * 60),
          array('method' => 'popup', 'minutes' => 10),
        ),
      ),
    ));
    
    $event = $service->events->insert($calendarId, $event);
}

function getCalendarTypes($client){
    $service = new Google_Service_Calendar($client);    
    $results = $service->calendarList->listCalendarList();
    return $results->getItems();
}

function getCalIdByName($client, $name = "helpTrain"){
    $exsistingCalendars = getCalendarTypes($client);
    foreach($exsistingCalendars as $exsistCal){
        if ($exsistCal->getSummary() == $name){
            return($exsistCal->getId());
        }
    }
    return NULL;    
}


//Ok, now we do the actual work of adding the date to the calendar:
$client = getClient();
$service = new Google_Service_Calendar($client);  //<- global-ish

$calendarId = getCalIdByName($client);
$now = new DateTime();
$later = $now->add(new DateInterval('P'.rand(1,14).'D'));
addEvent($service,
         $calendarId,
         new Person("Harry","giblfiz@gmail.com","6102206245"),
         $later);



// Set the content-type to XML to send back TwiML from the PHP Helper Library
header("content-type: text/xml");
$response = new MessagingResponse();
$response->message(
    "Ok, you have been added to the calendar for " . $later->format(DateTime::RFC2822)
);
echo $response;
