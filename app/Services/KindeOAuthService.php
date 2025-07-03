<?php

namespace App\Services;

use League\OAuth2\Client\Provider\GenericProvider;

class KindeOAuthService {
    public static function makeProvider() {
        return new GenericProvider([
            'clientId'                => config('services.kinde.client_id'),
            'clientSecret'            => config('services.kinde.client_secret'),
            'redirectUri'             => config('services.kinde.redirect'),
            'urlAuthorize'            => config('services.kinde.base_uri') . '/oauth2/auth',
            'urlAccessToken'          => config('services.kinde.base_uri') . '/oauth2/token',
            'urlResourceOwnerDetails' => config('services.kinde.base_uri') . '/oauth2/user_info',
            'scopes'                  => env('KINDE_SCOPES'),
        ]);
    }
}