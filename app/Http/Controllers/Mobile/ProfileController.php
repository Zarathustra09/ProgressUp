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


    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image',
        ]);

        $user = $request->user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_pictures', 'public');
            $user->profile_image = $path;
            $user->save();
        }

        return response()->json(['message' => 'Profile picture updated successfully', 'profile_image' => $path]);
    }


}
