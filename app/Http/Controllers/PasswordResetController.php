<?php
// app/Http/Controllers/PasswordResetController.php

namespace App\Http\Controllers;

use App\Services\KindeUserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller {
    public function __construct(private KindeUserManagementService $kinde) {
    }

    public function showForgotForm() {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request) {
        $data = $request->validate(['email' => ['required', 'email']]);

        try {
            $user = $this->kinde->findUserByEmail($data['email']);

            if ($user && !empty($user['id'])) {
                $this->kinde->requestPasswordReset($user['id']);
            }

            // Do not reveal whether the email exists (prevents user enumeration)
            return back()->with('status', 'If an account exists for that email, a reset link will be sent.');
        } catch (\Throwable $e) {
            Log::error('Reset request failed', [
                'email' => $data['email'],
                'error' => $e->getMessage(),
            ]);

            // Still return generic success
            return back()->with('status', 'If an account exists for that email, a reset link will be sent.');
        }
    }

    public function showForm() {
        // simple blade with a user_id field and two buttons
        return view('tools.reset-password');
    }

    // 1) Set password (used to reproduce)
    public function setPassword(Request $request, KindeUserManagementService $svc) {
        $request->validate([
            'user_id' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $userId = $request->input('user_id');
        $password = $request->input('password') ?: 'TempPassw0rd!';

        Log::info('[KindeTest] SetPassword:start', ['user_id' => $userId]);

        try {
            // uses your PUT /api/v1/users/{id}/password with hashing_method=bcrypt
            $svc->setTemporaryPassword($userId, $password);

            Log::info('[KindeTest] SetPassword:success', ['user_id' => $userId]);

            return back()->with('status', "Password was set for user {$userId}");
        } catch (\Throwable $e) {
            Log::error('[KindeTest] SetPassword:error', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', "Set password failed: " . $e->getMessage());
        }
    }

    public function setPermanentPassword(Request $r, KindeUserManagementService $svc) {
        $r->validate(['user_id' => ['required'], 'password' => ['nullable', 'string', 'min:8']]);
        $id = $r->user_id;
        $pwd = $r->password ?: 'PermPassw0rd!';
        Log::info('[KindeTest] SetPermanentPassword:start', ['user_id' => $id]);
        try {
            $svc->setPermanentPassword($id, $pwd);
            Log::info('[KindeTest] SetPermanentPassword:success', ['user_id' => $id]);
            return back()->with('status', "Permanent password set for {$id}");
        } catch (\Throwable $e) {
            Log::error('[KindeTest] SetPermanentPassword:error', ['user_id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', "Set permanent password failed: " . $e->getMessage());
        }
    }


    // 2) Request reset flag (expected to fail for the repro)
    public function requestReset(Request $request, KindeUserManagementService $svc) {
        $request->validate([
            'user_id' => ['required', 'string'],
        ]);

        $userId = $request->input('user_id');

        Log::info('[KindeTest] RequestReset:start', ['user_id' => $userId]);

        try {
            // uses PATCH /api/v1/user { id, is_password_reset_requested: true }
            $svc->requestPasswordReset($userId);

            Log::info('[KindeTest] RequestReset:success', ['user_id' => $userId]);

            return back()->with('status', "Reset flag set for user {$userId}");
        } catch (\Throwable $e) {
            // Important: include the raw API error for screenshots
            Log::error('[KindeTest] RequestReset:error', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', "Reset request failed: " . $e->getMessage());
        }
    }
}
