<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoomStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 3)->get();
        return view('staff.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users',
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:rooms,id',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'province' => $request->province,
                'birthdate' => $request->birthdate,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'branch_id' => $request->branch_id,
                'role_id' => 3,
            ]);

            if ($request->branch_id) {
                RoomStaff::create([
                    'room_id' => $request->branch_id,
                    'staff_id' => $user->id,
                ]);
            }

            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Staff created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('staff.index')->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $user->id,
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'branch_id' => 'nullable|exists:rooms,id',
        ]);

        DB::beginTransaction();

        try {
            $user->update($request->all());

            if ($request->branch_id) {
                RoomStaff::updateOrCreate(
                    ['staff_id' => $user->id],
                    ['room_id' => $request->branch_id]
                );
            }

            DB::commit();

            return response()->json(['success' => 'Staff updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update staff: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            // Delete associated RoomStaff records
            RoomStaff::where('staff_id', $user->id)->delete();

            // Delete the user
            $user->delete();

            DB::commit();

            return response()->json(['success' => 'Staff deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete staff: ' . $e->getMessage()], 500);
        }
    }
}
