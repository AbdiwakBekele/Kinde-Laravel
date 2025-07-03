<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KindeService {
    public function deleteUser($userId, $deleteProfile = true) {

        $url = config('services.kinde.base_uri') . '/api/v1/user';
        $query = [
            'id' => $userId,
            'is_delete_profile' => $deleteProfile ? 'true' : 'false'
        ];

        $response = Http::withToken(config('services.kinde.api_token'))
            ->delete($url, $query);

        if ($response->successful()) {
            return true;
        }

        throw new \Exception('Failed to delete user: ' . $response->body());
    }
}