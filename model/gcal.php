<?php

/**
 *This is going to manage interaction with the visitors google calendar
 *it's a singleton so we only need to connect once
 *
 */
class GCAL{
    private static $instance = NULL;   // Hold the class instance.
    public $client;
    public $service;
    public $calendarId;
    
    function GCAL(){
      if (self::$instance == NULL){
      self::$instance = new GCAL();
    }
    return self::$instance;
    }

    
    // * Builds an authorized API client.
    function __construct(){
        $client = new Google_Client();
        $client->setApplicationName('Baby Help Train');
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
        $this->client = $client;
        $this->service = new Google_Service_Calendar($client);
    }    

    function addEvent(Person $who, DateTime $when){
    
        $event = new Google_Service_Calendar_Event(array(
          'summary' => $who->name . ' visits the home',
          'location' => '800 Howard St., San Francisco, CA 94103',
          'description' => 'Helping with Baby Care',
          'start' => array(
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
        
        $event = $this->service->events->insert($this->calendarId, $event);
    }
    
    function getCalendarTypes(){
        $service = new Google_Service_Calendar($this->client);    
        $results = $service->calendarList->listCalendarList();
        return $results->getItems();
    }

    /**
     *Ok, so in theory we should never need this again
     *Because we are going to store the calendar ID after we craft it
     */
    function getCalIdByName($name = "helpTrain"){
        $exsistingCalendars = $this->getCalendarTypes();
        foreach($exsistingCalendars as $exsistCal){
            if ($exsistCal->getSummary() == $name){
                return($exsistCal->getId());
            }
        }
        return NULL;
    }


}