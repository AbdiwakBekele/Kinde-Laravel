<?php

namespace App\Http\Controllers;

use App\Services\KindeM2MService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KindeOrgPermissionReproController extends Controller {
    public function form() {
        return view('kinde-perm-form');
    }

    public function run(Request $request, KindeM2MService $m2m) {
        $data = $request->validate([
            'org_code'        => 'required|string',
            'user_id'         => 'required|string',
            'permission_key'  => 'required|string', // e.g. "read:stuff"
            'permission_id'   => 'nullable|string', // e.g. "perm_123..." (optional; used for step D)
            'method'          => 'required|in:POST,PATCH', // bulk endpoint verb to try
        ]);

        $token   = $m2m->getAccessToken();
        if (!$token) {
            return back()->withErrors(['m2m' => 'Failed to obtain M2M access token. Check KINDE_M2M_* env and audience/scopes.']);
        }

        $base    = rtrim(config('services.kinde_m2m.base_uri'), '/');
        $orgCode = $data['org_code'];
        $userId  = $data['user_id'];
        $pKey    = $data['permission_key'];
        $pId     = $data['permission_id'] ?? null;
        $method  = $data['method'];

        $log = [];

        // Helper to wrap responses for easy viewing
        $wrap = function ($res) {
            return [
                'status'  => $res->status(),
                'ok'      => $res->ok(),
                'json'    => json_decode($res->body(), true),
                'raw'     => $res->body(),
                'headers' => $res->headers(),
            ];
        };

        // A) Clear permissions (empty array) via bulk update
        $bulkUrl = "{$base}/api/v1/organizations/{$orgCode}/users";
        $clearPayload = [
            'users' => [[
                'id'          => $userId,
                'permissions' => [],   // replace with empty set
            ]],
        ];
        $resA = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->{$method}($bulkUrl, $clearPayload);
        $log['A_clear_permissions'] = [
            'request'  => ['url' => $bulkUrl, 'method' => $method, 'body' => $clearPayload],
            'response' => $wrap($resA),
        ];

        // B) Try to add the permission using the bulk endpoint with a KEY
        $bulkPayload = [
            'users' => [[
                'id'          => $userId,
                'permissions' => [$pKey],  // KEY-based update
            ]],
        ];
        $resB = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->{$method}($bulkUrl, $bulkPayload);
        $log['B_bulk_add_by_key'] = [
            'request'  => ['url' => $bulkUrl, 'method' => $method, 'body' => $bulkPayload],
            'response' => $wrap($resB),
        ];

        // C) Read back user permissions (org-scoped)
        // 🔧 FIX: correct interpolation — NO spaces inside the braces
        $permUrl  = "{$base}/api/v1/organizations/{$orgCode}/users/{$userId}/permissions";
        $rolesUrl = "{$base}/api/v1/organizations/{$orgCode}/users/{$userId}/roles";


        $resPerm = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get($permUrl);

        $resRoles = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get($rolesUrl);

        $log['C_read_back_user'] = [
            ['permissions_url' => $permUrl, 'response' => $wrap($resPerm)],
            ['roles_url'       => $rolesUrl, 'response' => $wrap($resRoles)],
        ];

        // D) If still no permission and a permission_id is provided, add via single endpoint
        if ($pId) {
            $singleUrl = "{$base}/api/v1/organizations/{$orgCode}/users/{$userId}/permissions";
            $singlePayload = ['permission_id' => $pId];
            $resD = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/json'])
                ->post($singleUrl, $singlePayload);

            $log['D_single_add_by_id'] = [
                'request'  => ['url' => $singleUrl, 'method' => 'POST', 'body' => $singlePayload],
                'response' => $wrap($resD),
            ];

            // E) Read back again (permissions & roles) — 🔧 FIX: don't reference undefined vars
            $resEPerm = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($permUrl);

            $resERoles = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($rolesUrl);

            $log['E_read_back_after_single'] = [
                ['permissions_url' => $permUrl, 'response' => $wrap($resEPerm)],
                ['roles_url'       => $rolesUrl, 'response' => $wrap($resERoles)],
            ];
        }

        return view('kinde-perm-result', ['log' => $log]);
    }
}
