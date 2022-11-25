<?php

namespace App\GoogleServices;

use Google_Client;
use Google\Exception;
use Illuminate\Http\RedirectResponse;
use Google\Auth\Cache\InvalidArgumentException;

class GoogleOauth2
{
    private Google_Client $googleClient;
    private const REDIRECT_URI_CALENDAR = '/settings';

    /**
     * @return Google_Client
     */
    public function getGoogleClient () : Google_Client
    {
        return $this->googleClient;
    }

    /**
     * @param Google_Client $googleClient
     * @param string        $scope
     *
     * @throws Exception
     */
    public function __construct (Google_Client $googleClient, string $scope)
    {
        $this->googleClient = $googleClient;
        $this->_setUpConfig($scope);
    }

    /**
     * @throws Exception
     */
    private function _setUpConfig (string $scope)
    {
        $this->googleClient->setLoginHint(auth()->user()->email);
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setPrompt('consent');
        $this->googleClient->setAuthConfig(config('filesystems.disks.credentials.oauth2_google_api'));
        $this->googleClient->setRedirectUri(config('app.front_end_url') . self::REDIRECT_URI_CALENDAR);
        $this->googleClient->addScope($scope);
        $this->googleClient->setIncludeGrantedScopes(true);
    }

    public function setToken (?array $token)
    {
        if (!is_null($token))
        {
            $this->googleClient->setAccessToken($token);
        }
    }

    public function authenticate (?string $email = null) : string
    {
        if (!is_null($email))
        {
            $this->googleClient->setLoginHint($email);
        }

        return $this->googleClient->createAuthUrl();
    }

    public function authorize (string $authCode)
    {
        $this->googleClient->fetchAccessTokenWithAuthCode($authCode);
    }

    public function fetchAccessTokenWithRefreshToken () : array
    {
        return $this->googleClient->fetchAccessTokenWithRefreshToken();
    }

    public function getToken () : array
    {
        return $this->googleClient->getAccessToken();
    }

    public function isAccessTokenExpired () : bool
    {
        return $this->googleClient->isAccessTokenExpired();
    }
}