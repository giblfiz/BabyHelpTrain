<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/person.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
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
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient(); //global-ish
$service = new Google_Service_Calendar($client);  //<- global-ish


function getEvents($service,
                   $calendarId = 'l9dja7694jfqbpvqucbm722on51g0i4n@import.calendar.google.com'){
    // Print the next 10 events on the user's calendar.
    //$calendarId = 'primary';
    //$calendarId = 'l9dja7694jfqbpvqucbm722on51g0i4n@import.calendar.google.com';
    $optParams = array(
      'maxResults' => 10,
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();
    
    if (empty($events)) {
        print "No upcoming events found.\n";
    } else {
        print "Upcoming events:\n";
        foreach ($events as $event) {
            $start = $event->start->dateTime;
            if (empty($start)) {
                $start = $event->start->date;
            }
            printf("%s (%s)\n", $event->getSummary(), $start);
        }
    }
}

function addEvent($service, $calendarId, Person $who, DateTime $when){
    // Refer to the PHP quickstart on how to setup the environment:
    // https://developers.google.com/calendar/quickstart/php
    // Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
    // credentials.
    
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
    
//    $calendarId = 'primary';
    $event = $service->events->insert($calendarId, $event);
    printf('Event created: %s\n', $event->htmlLink);
}

//testAddingEvent();

function getCalendarTypes($client, $echo = false){
    $service = new Google_Service_Calendar($client);
//    $calList = new Google_Service_Calendar_CalendarList($client);
    
    $results = $service->calendarList->listCalendarList();
    $items = $results->getItems();
    echo "\n ======== \n";
    if($echo){
        foreach ($items as $entry){
            echo $entry->getSummary() . "  id:" . $entry->getId();
            echo ("\n");
        }
    }
//    echo "\n ======== \n";
  //      print_r( get_class_methods($entry));
  return($items);
}

function createCal($client, $name="helpTrain"){
    $calendarId = getCalIdByName($client, $name);
    if (!is_null($calendarId)){
        return $calendarId;
    }
    
    $service = new Google_Service_Calendar($client);
    $calendar = new Google_Service_Calendar_Calendar();
    $calendar->setSummary($name);
    $calendar->setTimeZone('America/Los_Angeles');

    $createdCalendar = $service->calendars->insert($calendar);

    return $createdCalendar->getId();
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


//createCal($client);
//getCalendarTypes($client,true);
$calendarId = getCalIdByName($client);
getEvents($service,$calendarId);
$now = new DateTime();
$later = $now->add(new DateInterval('P'.rand(1,14).'D'));
addEvent($service,
         $calendarId,
         new Person("Harry","giblfiz@gmail.com","6102206245"),
         $later);



//$time = DateTime::createFromFormat(DateTime::RFC3339, '2019-08-12T17:00:00-07:00');

//echo $time->format(DateTime::RFC3339);
//    echo "\n ======== \n";
//echo $time->add(new DateInterval('PT4H'))->format(DateTime::RFC3339);
//    echo "\n ======== \n";
