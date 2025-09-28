<?php

namespace App\Http\Controllers;

use App\Services\KindeService;
use App\Services\KindeUserManagementService;
use Illuminate\Http\Request;
use App\Services\KindeM2MService;

class KindeUserController extends Controller {
    protected $kinde;

    public function __construct(KindeService $kinde) {
        $this->kinde = $kinde;
    }


    public function form() {
        return view('dashboard'); // or a dedicated blade; see sample below
    }

    public function addUsers(Request $request, KindeM2MService $m2m, KindeUserManagementService $svc) {
        $data = $request->validate([
            'org_code' => 'required|string',
            'user_ids' => 'required|string', // comma separated
            'roles'    => 'nullable|string', // comma separated roles
        ]);

        $token = $m2m->getToken();
        if (!$token) {
            return back()->withErrors('Failed to get M2M token. Check credentials/permissions.');
        }

        $ids = collect(explode(',', $data['user_ids']))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values();

        $roles = collect(explode(',', (string) ($data['roles'] ?? 'owner')))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->toArray();

        $payloadUsers = $ids->map(fn($id) => ['id' => $id, 'roles' => $roles])->all();

        $res = $svc->addUsersToOrg($data['org_code'], $payloadUsers, $token);

        return back()->with([
            'kinde_request'  => ['org' => $data['org_code'], 'users' => $payloadUsers],
            'kinde_response' => $res,
        ]);
    }

    public function delete($userId, KindeUserManagementService $service) {

        try {
            $service->deleteUser($userId);
            return response()->json(['message' => 'User successfully deleted from Kinde.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
