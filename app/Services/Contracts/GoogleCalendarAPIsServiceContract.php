<?php

namespace App\Services\Contracts;

interface GoogleCalendarAPIsServiceContract
{
    public function authenticate ();

    public function authorize (string $authCode);

    public function getCalendarList ();

    public function getAllEvents ();

    public function getEventsByCalendarId (string $calendarId, array $optionParameters = []);

    public function createEvent (string $calendarId, array $inputs, array $optionParameters);

    public function updateEvent (string $calendarId, string $eventId, array $inputs, array $optionParameters);

    public function destroyEvent (string $calendarId, string $eventId, array $optionParameters);

    public function getAllEventsOfAllCalendars (array $optionParameters);

    public function googleAPIsRevoke ();
}