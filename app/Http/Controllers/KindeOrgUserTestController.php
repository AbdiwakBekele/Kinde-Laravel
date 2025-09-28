<?php

namespace App\Http\Controllers;

use App\Services\KindeM2MService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KindeOrgUserTestController extends Controller {
    public function form() {
        return view('kinde-test-form');
    }

    public function run(Request $request, KindeM2MService $m2m) {
        $request->validate([
            'org_code' => 'required|string',
            'scenario' => 'required|in:valid,none,already_member,malformed',
            'user_id'  => 'nullable|string',
            'roles'    => 'nullable|string', // comma separated
        ]);

        $domain = rtrim(config('services.kinde.base_uri'), '/');
        $token  = $m2m->getToken();
        if (!$token) return back()->withErrors('M2M token failed');

        $roles = collect(explode(',', (string) $request->roles ?: 'owner'))
            ->map(fn($s) => trim($s))->filter()->values()->toArray();

        switch ($request->scenario) {
            case 'valid':
            case 'already_member':
                if (!$request->user_id) return back()->withErrors('User ID required.');
                $payload = ['users' => [['id' => $request->user_id, 'roles' => $roles]]];
                break;
            case 'none':
                $payload = ['users' => []];
                break;
            case 'malformed':
                $payload = ['id' => $request->user_id ?: 'kp_placeholder', 'roles' => $roles];
                break;
        }

        $url = "{$domain}/api/v1/organizations/{$request->org_code}/users";
        $res = Http::withToken($token)->post($url, $payload);

        return view('kinde-test-result', [
            'requestData'  => ['url' => $url, 'body' => $payload],
            'responseData' => [
                'status'  => $res->status(),
                'ok'      => $res->ok(),
                'raw'     => $res->body() === '' ? '<no body>' : $res->body(),
                'json'    => json_decode($res->body(), true),
                'headers' => $res->headers(),
            ],
        ]);
    }
}
