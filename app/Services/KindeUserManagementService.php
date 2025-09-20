<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class KindeUserManagementService {
    protected $m2m;

    public function __construct(KindeM2MService $m2m) {
        $this->m2m = $m2m;
    }

    protected function base(): string {
        return rtrim(config('services.kinde_m2m.base_uri'), '/');
    }

    /**
     * Find user by email via Management API.
     * Returns first user (array) or null.
     */
    public function findUserByEmail(string $email): ?array {
        $token = $this->m2m->getToken();

        $resp = Http::withToken($token)->get($this->base() . '/api/v1/users', [
            'email' => $email,
            'page_size' => 1,
        ]);

        if (!$resp->ok()) {
            throw new \RuntimeException('User search failed: ' . $resp->body());
        }

        $users = $resp->json('users') ?? [];
        return $users[0] ?? null;
    }

    /**
     * Trigger Kinde to send a reset email by setting is_password_reset_requested = true.
     * Uses PATCH /api/v1/user with body { id, is_password_reset_requested }.
     */
    public function requestPasswordReset(string $userId): void {
        $token = $this->m2m->getToken();

        $resp = Http::withToken($token)->patch($this->base() . '/api/v1/user', [
            'id' => $userId,
            'is_password_reset_requested' => true,
        ]);

        if (!$resp->ok()) {
            throw new \RuntimeException('Password reset request failed: ' . $resp->body());
        }
    }


    /**
     * Optional fallback for PASSWORD_REQUEST_INVALID:
     * Set a temporary password that forces change on next login.
     * Requires update:user_passwords scope.
     */
    public function setTemporaryPassword(string $userId, string $plainPassword): void {
        $token = $this->m2m->getToken();

        $resp = Http::withToken($token)->put($this->base() . "/api/v1/users/{$userId}/password", [
            'hashed_password' => Hash::make($plainPassword), // bcrypt
            'hashing_method' => 'bcrypt',
            'is_temporary_password' => true,
        ]);

        if (!$resp->ok()) {
            throw new \RuntimeException('Set temp password failed: ' . $resp->body());
        }
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
