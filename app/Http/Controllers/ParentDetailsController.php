<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ParentDetailsController extends Controller
{
    public function index($id)
    {
        $parent = User::where('id', $id)->where('role_id', 0)->firstOrFail();
        return view('parentDetails.index', compact('parent'));
    }

    public function indexStudent($id)
    {
        $students = User::where('parent_id', $id)->get();
        return view('parentDetails.student.index', compact('students', 'id'));
    }

    public function create($id)
    {
        return view('parentDetails.student.create', compact('id'));
    }


    public function store(Request $request, $id)
    {
        Log::info('This is the id: ' . $id);
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
            'allergies' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
            'medication' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'profile_image_data' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $studentId = date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            $student = new User([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'province' => $request->province,
                'birthdate' => $request->birthdate,
                'password' => bcrypt($request->password),
                'role_id' => 1,
                'parent_id' => $id,
            ]);

            if ($request->profile_image_data) {
                $imageData = $request->profile_image_data;
                $imageName = 'profile_images/' . uniqid() . '.png';
                Storage::disk('public')->put($imageName, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));
                $student->profile_image = $imageName;
            }

            $student->save();

            $student->studentMedicalInformation()->create([
                'allergies' => $request->allergies,
                'notes' => $request->notes,
                'medication' => $request->medication,
            ]);

            $student->studentSchoolDetails()->create([
                'student_id' => $studentId,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('parent-student.index', ['id' => $id])
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create student: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('parent-student.index', ['id' => $id])
                ->with('error', 'Failed to create student.');
        }
    }

}
