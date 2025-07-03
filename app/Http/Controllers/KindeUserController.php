<?php

namespace App\Http\Controllers;

use App\Services\KindeService;
use App\Services\KindeUserManagementService;
use Illuminate\Http\Request;

class KindeUserController extends Controller {
    protected $kinde;

    public function __construct(KindeService $kinde) {
        $this->kinde = $kinde;
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