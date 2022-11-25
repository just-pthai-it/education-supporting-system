<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateGoogleEventPostRequest;
use App\Http\Requests\UpdateGoogleEventPatchRequest;
use App\Http\Requests\AuthorizeGoogleAPIsPostRequest;
use App\Services\Contracts\GoogleCalendarAPIsServiceContract;
use App\Http\Requests\GoogleAPIs\Calendar\DestroyGoogleEventDeleteRequest;

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

    public function getEventsByCalendarId (Request $request, string $uuidAccount,
                                           string  $calendarId)
    {
        return $this->googleCalendarService->getEventsByCalendarId($calendarId, $request->all());
    }

    public function storeEvent (CreateGoogleEventPostRequest $request, string $uuidAccount,
                                string                       $calendarId)
    {
        $this->googleCalendarService->createEvent($calendarId, $request->all());
    }

    public function updateEvent (UpdateGoogleEventPatchRequest $request, string $uuidAccount,
                                 string                        $calendarId, string $eventId)
    {
        $this->googleCalendarService->updateEvent($calendarId, $eventId, $request->all());
    }

    public function destroyEvent (DestroyGoogleEventDeleteRequest $request, string $uuidAccount,
                                  string                          $calendarId, string $eventId)
    {
        $this->googleCalendarService->destroyEvent($calendarId, $eventId, $request->all());
    }
}
