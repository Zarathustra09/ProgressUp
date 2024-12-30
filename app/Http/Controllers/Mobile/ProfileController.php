<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function getProfile(Request $request, $user_id)
    {
        $user = User::with(['studentMedicalInformation', 'studentSchoolDetails', 'branch'])->find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($request->user()->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        \Log::info('User profile accessed', ['user' => $user]);

        return response()->json($user);
    }
}
