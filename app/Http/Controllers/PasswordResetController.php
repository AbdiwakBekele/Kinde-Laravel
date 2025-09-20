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
}
