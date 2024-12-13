<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show the form for editing the current user's profile
    public function index()
    {
        $branches = Room::all();
        return view('profile.index', compact('branches'));
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
            'branch_id' => 'nullable|exists:rooms,id',
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
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function uploadProfileImage(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:800',
            ]);

            if ($request->hasFile('profile_image')) {
                // Add more detailed logging
                $file = $request->file('profile_image');
                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);

                $imagePath = $file->store('profile_images', 'public');

                // Verify the path was generated
                if (!$imagePath) {
                    Log::error('Failed to store image');
                    return back()->with('error', 'Image upload failed');
                }

                $user->profile_image = $imagePath;

                // Add error handling for save
                if (!$user->save()) {
                    Log::error('Failed to save user profile image path');
                    return back()->with('error', 'Could not update profile image');
                }
            }

            return redirect()->route('profile.index')->with('success', 'Profile image updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile image upload error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while uploading the image');
        }
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

        Log::info('Profile image reset process completed successfully.');

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
