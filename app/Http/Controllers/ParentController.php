<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 0)->get();
        return view('parent.index', compact('users'));
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
            'branch_id' => 'nullable|exists:rooms,id', // Validate branch_id
        ]);

        User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'province' => $request->province,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'branch_id' => $request->branch_id, // Store branch_id
        ]);

        return redirect()->route('parent.index')->with('success', 'User created successfully.');
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
            'branch_id' => 'nullable|exists:rooms,id', // Validate branch_id
        ]);

        DB::beginTransaction();

        try {
            $user->update($request->all());

            // Update branch_id and RoomStudent for each student of the parent
            $students = User::where('parent_id', $user->id)->get();
            foreach ($students as $student) {
                $student->update(['branch_id' => $request->branch_id]);
                $student->roomStudent()->updateOrCreate(
                    [],
                    [
                        'room_id' => $request->branch_id,
                    ]
                );
            }

            DB::commit();

            return response()->json(['success' => 'User updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }
}
