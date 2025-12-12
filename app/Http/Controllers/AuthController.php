<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    public function redirectToKinde(Request $request) {

        $state = Str::random(32); // secure random string
        session(['oauth_state' => $state]); // store in session

        $queryParams = [
            'client_id' => config('services.kinde.client_id'),
            'redirect_uri' => config('services.kinde.redirect'),
            'response_type' => 'code',
            'scope' => env('KINDE_SCOPES'),
            'state' => $state,
        ];

        // Add login_hint - use request parameter if provided, otherwise use static test value
        if ($request->has('login_hint') && !empty($request->get('login_hint'))) {
            $queryParams['login_hint'] = $request->get('login_hint');
        } else {
            // Static login_hint for testing purposes
            $queryParams['login_hint'] = 'abdiwakbek3226@gmail.com';

            // Static login_hint for testing purposes (username format: username:value)
            // $queryParams['login_hint'] = 'username:abdiwak';
        }

        // Prompt user to create an account
        // $queryParams['prompt'] = 'create';

        $query = http_build_query($queryParams);
        $authUrl = config('services.kinde.base_uri') . '/oauth2/auth?' . $query;

        // Log the authorization URL for debugging
        Log::info('Kinde Authorization URL', [
            'url' => $authUrl,
            'login_hint' => $request->get('login_hint'),
            'has_login_hint' => $request->has('login_hint'),
        ]);

        return redirect($authUrl);
    }

    public function handleKindeCallback(Request $request) {

        $expectedState = session('oauth_state');
        $receivedState = $request->get('state');

        if (!$expectedState || $expectedState !== $receivedState) {
            abort(403, 'Invalid state value');
        }

        $response = Http::asForm()->post(config('services.kinde.base_uri') . '/oauth2/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.kinde.client_id'),
            'client_secret' => config('services.kinde.client_secret'),
            'redirect_uri' => config('services.kinde.redirect'),
            'code' => $request->code,
        ]);

        $tokenData = $response->json();

        Log::info('AUth');
        Log::info($tokenData);

        // Decode ID token to extract user info
        // $idToken = $tokenData['id_token'];
        // $user = json_decode(base64_decode(explode('.', $idToken)[1]), true);

        // Log::info($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request) {
        // Optionally clear your own session and auth
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Build Kinde logout URL
        $query = http_build_query([
            'client_id' => config('services.kinde.client_id'),
            'post_logout_redirect_uri' => config('services.kinde.logout_redirect'),
        ]);

        return redirect(config('services.kinde.base_uri') . '/logout?' . $query);
    }
}
