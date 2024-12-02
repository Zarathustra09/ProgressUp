<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show the form for editing the current user's profile
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        Log::info('Profile update request', $request->all());
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $user->id,
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'province' => $request->province,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function uploadProfileImage(Request $request)
    {
        Log::info('Profile image upload request', $request->all());
        $user = Auth::user();

        Log::info('Profile image upload request', [
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:800',
        ]);

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            Log::info('Profile image uploaded', ['image_path' => $imagePath]);

            $user->profile_image = $imagePath;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Profile image updated successfully.');
    }

    public function resetProfileImage(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_image) {
            // Delete the image file from storage
            Storage::disk('public')->delete($user->profile_image);
            // Remove the image path from the database
            $user->profile_image = null;
            $user->save();
        }

        return response()->json(['success' => 'Profile image reset successfully.']);
    }

    public function destroy()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
