<?php

namespace App\Services;

use Google_Client;
use Google\Exception;
use Google_Service_Calendar;
use App\Models\ThirdPartyToken;
use App\GoogleServices\GoogleOauth2;
use App\GoogleServices\GoogleAPIs\GoogleCalendarAPIs;
use App\Exceptions\InvalidGoogleApiTokenException;
use App\Repositories\Contracts\ThirdPartyTokenRepositoryContract;

class GoogleCalendarAPIsService implements Contracts\GoogleCalendarAPIsServiceContract
{
    private ThirdPartyTokenRepositoryContract $thirdPartyTokenRepository;
    private GoogleCalendarAPIs                $googleCalendarAPIs;
    private GoogleOauth2                      $googleOauth2;
    private ?ThirdPartyToken                  $thirdPartyToken;

    /**
     * @param ThirdPartyTokenRepositoryContract $thirdPartyTokenRepository
     * @param Google_Client                     $googleClient
     *
     * @throws Exception
     */
    public function __construct (ThirdPartyTokenRepositoryContract $thirdPartyTokenRepository,
                                 Google_Client                     $googleClient)
    {
        $this->thirdPartyTokenRepository = $thirdPartyTokenRepository;
        $this->thirdPartyToken           = $this->thirdPartyTokenRepository->findOne(['*'],
                                                                                     [['id_account', '=', auth()->user()->id]]);
        $this->googleOauth2              = new GoogleOauth2($googleClient,
                                                            Google_Service_Calendar::CALENDAR);
        $this->googleCalendarAPIs        = new GoogleCalendarAPIs($this->googleOauth2->getGoogleClient());
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    private function __setToken (bool $isAuthenticating = false) : void
    {
        $this->googleOauth2->setToken($this->__verifyToken($isAuthenticating));
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    private function __verifyToken (bool $isAuthenticating) : ?array
    {
        $token = $this->thirdPartyToken->google_token ?? null;
        if (is_null($token) || !isset($token['access_token'], $token['refresh_token']))
        {
            if ($isAuthenticating)
            {
                return null;
            }

            throw new InvalidGoogleApiTokenException("invalid token", 451);
        }

        return $token;
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function authenticate ()
    {
        $this->__setToken(true);
        return response(['data' => ['authUrl' => $this->googleOauth2->authenticate()]]);
    }

    public function authorize (string $authCode) : void
    {
        $this->googleOauth2->authorize($authCode);
        $this->__upsertThirdPartyToken();
    }

    private function __upsertThirdPartyToken () : void
    {
        if (is_null($this->thirdPartyToken))
        {
            $this->thirdPartyTokenRepository->insert(['id_account'   => auth()->user()->id,
                                                      'google_token' => $this->googleOauth2->getToken()]);
        }
        else
        {
            $this->thirdPartyToken->google_token = $this->googleOauth2->getToken();
            $this->thirdPartyToken->save();
        }
    }

    private function _refreshTokenIfNeeded () : void
    {
        if ($this->__isAccessTokenExpired())
        {
            $token = $this->googleOauth2->fetchAccessTokenWithRefreshToken();
            if (isset($token['error']))
            {
                abort(451);
            }

            $this->__upsertThirdPartyToken();
        }
    }

    private function __isAccessTokenExpired () : bool
    {
        return $this->googleOauth2->isAccessTokenExpired();
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function getCalendarList () : array
    {
        $this->__setToken();
        $calendars = $this->googleCalendarAPIs->getCalendarList();
        $data      = [];

        foreach ($calendars as $calendar)
        {
            $data[] = [
                'accessRole'      => $calendar->getAccessRole(),
                'id'              => $calendar->getId(),
                'summary'         => $calendar->getSummary(),
                'description'     => $calendar->getDescription(),
                'colorId'         => $calendar->getColorId,
                'backgroundColor' => $calendar->getBackgroundColor,
            ];
        }
        return ['data' => $data];
    }

    public function getAllEvents ()
    {
        // TODO: Implement getAllEvents() method.
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function getEventsByCalendarId (string $calendarId, array $optionParameters = []) : array
    {
        $this->__setToken();

        try
        {
            $googleEvents = $this->googleCalendarAPIs->getEventsByCalendarId($calendarId,
                                                                             $optionParameters);
            return ['data' => $googleEvents];
        }
        catch (Exception $exception)
        {
            return ['data' => 'error'];
        }
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function createEvent (string $calendarId, array $inputs, array $optionParameters)
    {
        $this->__setToken();
        $this->googleCalendarAPIs->createEvent($calendarId, $inputs, $optionParameters);
        return response([], 201);
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function updateEvent (string $calendarId, string $eventId, array $inputs,
                                 array  $optionParameters)
    {
        $this->__setToken();
        $this->googleCalendarAPIs->updateEvent($calendarId, $eventId, $inputs, $optionParameters);
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function destroyEvent (string $calendarId, string $eventId, array $optionParameters)
    {
        $this->__setToken();
        $this->googleCalendarAPIs->destroyEvent($calendarId, $eventId, $optionParameters);
    }

    /**
     * @throws InvalidGoogleApiTokenException
     */
    public function getAllEventsOfAllCalendars (array $optionParameters)
    {
        $this->__setToken();
        $calendars = $this->googleCalendarAPIs->getCalendarList();
        $result    = [];
        foreach ($calendars as $calendar)
        {
            try
            {
                $googleEvents = $this->googleCalendarAPIs->getEventsByCalendarId($calendar->getId(),
                                                                                 $optionParameters);

                $result[] = [
                    'accessRole'      => $calendar->getAccessRole(),
                    'id'              => $calendar->getId(),
                    'summary'         => $calendar->getSummary(),
                    'description'     => $calendar->getDescription(),
                    'colorId'         => $calendar->getColorId,
                    'backgroundColor' => $calendar->getBackgroundColor,
                    'events'          => $googleEvents,
                ];

            }
            catch (Exception $exception)
            {
                return ['data' => 'error'];
            }
        }

        return response(['data' => $result]);
    }
}