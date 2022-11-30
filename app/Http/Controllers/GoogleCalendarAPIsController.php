<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\Contracts\GoogleCalendarAPIsServiceContract;
use App\Http\Requests\GoogleAPIs\AuthorizeGoogleAPIsPostRequest;
use App\Http\Requests\GoogleAPIs\Calendar\Event\GetGoogleEventGetRequest;
use App\Http\Requests\GoogleAPIs\Calendar\Event\UpdateGoogleEventPutRequest;
use App\Http\Requests\GoogleAPIs\Calendar\Event\CreateGoogleEventPostRequest;
use App\Http\Requests\GoogleAPIs\Calendar\Event\DestroyGoogleEventDeleteRequest;

class GoogleCalendarAPIsController extends Controller
{
    private GoogleCalendarAPIsServiceContract $googleCalendarService;

    /**
     * @param GoogleCalendarAPIsServiceContract $googleCalendarService
     */
    public function __construct (GoogleCalendarAPIsServiceContract $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function googleAPIsAuthenticate (Request $request)
    {
        return $this->googleCalendarService->authenticate();
    }

    public function googleAPIsAuthorize (AuthorizeGoogleAPIsPostRequest $request)
    {
        $this->googleCalendarService->authorize($request->auth_code);
    }

    public function getCalendarList ()
    {
        return $this->googleCalendarService->getCalendarList();
    }

    public function getEventsByCalendarId (GetGoogleEventGetRequest $request, string $uuidAccount,
                                           string                   $calendarId)
    {
        return $this->googleCalendarService->getEventsByCalendarId($calendarId,
                                                                   $request->validated());
    }

    public function storeEvent (CreateGoogleEventPostRequest $request, string $uuidAccount,
                                string                       $calendarId)
    {
        $optionParameterKeys = array_keys($request->query());
        return $this->googleCalendarService->createEvent($calendarId,
                                                         Arr::except($request->validated(),
                                                                     $optionParameterKeys),
                                                         Arr::only($request->validated(),
                                                                   $optionParameterKeys));
    }

    public function updateEvent (UpdateGoogleEventPutRequest $request, string $uuidAccount,
                                 string                      $calendarId, string $eventId)
    {
        $optionParameterKeys = array_keys($request->query());
        return $this->googleCalendarService->updateEvent($calendarId, $eventId,
                                                         Arr::except($request->validated(),
                                                                     $optionParameterKeys),
                                                         Arr::only($request->validated(),
                                                                   $optionParameterKeys));
    }

    public function destroyEvent (DestroyGoogleEventDeleteRequest $request, string $uuidAccount,
                                  string                          $calendarId, string $eventId)
    {
        $this->googleCalendarService->destroyEvent($calendarId, $eventId, $request->validated());
    }

    public function getAllEventsOfAllCalendars (GetGoogleEventGetRequest $request)
    {
        return $this->googleCalendarService->getAllEventsOfAllCalendars($request->validated());
    }
}
