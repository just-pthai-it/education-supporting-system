<?php

namespace App\GoogleServices\GoogleAPIs;


use Exception;
use Google_Client;
use Carbon\Carbon;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarAPIs
{
    private Google_Service_Calendar $calendarService;

    public function __construct (Google_Client $client)
    {
        $this->calendarService = new Google_Service_Calendar($client);
    }

    public function getCalendarList (array $optionsParameters = []) : array
    {
        return $this->calendarService->calendarList->listCalendarList($optionsParameters)
                                                   ->getItems();
    }

    public function getEventsByCalendarId (string $calendarId, array $optionParameters = []) : array
    {
        return $this->calendarService->events->listEvents($calendarId, $optionParameters)
                                             ->getItems();
    }

    public function createEvent (string $calendarId, array $data)
    {
        $this->calendarService->events->insert($calendarId,
                                               new Google_Service_Calendar_Event($data));
    }

    public function updateEvent (string $calendarId, string $eventId, array $data)
    {
        $this->calendarService->events->update($calendarId, $eventId,
                                               new Google_Service_Calendar_Event($data));
    }

    public function destroyEvent (string $calendarId, string $eventId, array $optionParams)
    {
        $this->calendarService->events->delete($calendarId, $eventId, $optionParams);
    }
}