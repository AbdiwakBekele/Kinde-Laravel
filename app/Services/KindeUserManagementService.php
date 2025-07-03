<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KindeUserManagementService {
    protected $m2m;

    public function __construct(KindeM2MService $m2m) {
        $this->m2m = $m2m;
    }

    public function deleteUser(string $userId) {
        $token = $this->m2m->getAccessToken();

        $response = Http::withToken($token)->withHeaders([
            'Accept' => 'application/json',
        ])->delete(config('services.kinde_m2m.base_uri') . '/api/v1/user?id=' . $userId);

        if ($response->status() === 204) {
            return true;
        }

        throw new \Exception('Failed to delete user: ' . $response->body());
    }
}