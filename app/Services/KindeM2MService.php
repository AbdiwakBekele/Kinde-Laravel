<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KindeM2MService {
    public function getAccessToken() {
        $response = Http::asForm()->post(config('services.kinde_m2m.base_uri') . '/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.kinde_m2m.client_id'),
            'client_secret' => config('services.kinde_m2m.client_secret'),
            'audience' => config('services.kinde_m2m.audience'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Unable to get Kinde M2M token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }

    public function getToken(): string {
        $resp = Http::asForm()->post(
            rtrim(config('services.kinde_m2m.base_uri'), '/') . '/oauth2/token',
            [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.kinde_m2m.client_id'),
                'client_secret' => config('services.kinde_m2m.client_secret'),
                'audience' => config('services.kinde_m2m.audience'),
            ]
        );

        if (!$resp->ok()) {
            throw new \RuntimeException('Failed to obtain M2M token: ' . $resp->body());
        }

        return $resp->json('access_token');
    }
}
