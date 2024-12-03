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

    public function show($id, $studentId)
    {
        $student = User::where('id', $studentId)->where('parent_id', $id)->firstOrFail();
        return view('parentDetails.student.show', compact('student', 'id'));
    }

    public function edit($id, $studentId)
    {
        $student = User::where('id', $studentId)->where('parent_id', $id)->firstOrFail();
        return view('parentDetails.student.edit', compact('student', 'id'));
    }

    public function update(Request $request, $id, $studentId)
    {
        Log::info('Update student request', [
            'parent_id' => $id,
            'student_id' => $studentId,
            'data' => $request->except(['password', 'password_confirmation', 'profile_image_data'])
        ]);

        $student = User::where('id', $studentId)
            ->where('parent_id', $id)
            ->firstOrFail();

        $validationRules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'password' => 'nullable|string|min:8|confirmed',
            'allergies' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
            'medication' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'profile_image_data' => 'nullable|string',
        ];

        $request->validate($validationRules);

        DB::beginTransaction();

        try {
            // Prepare user data
            $userData = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'province' => $request->province,
                'birthdate' => $request->birthdate,
            ];

            // Update password only if provided
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            // Handle profile image upload
            if ($request->profile_image_data) {
                try {
                    $imageData = $request->profile_image_data;

                    // Remove data URI scheme prefix
                    $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);

                    // Decode base64
                    $imageDecoded = base64_decode($imageData);

                    // Validate image
                    if (!$imageDecoded) {
                        throw new \Exception('Invalid base64 image data');
                    }

                    // Generate unique filename
                    $imageName = 'profile_images/' . uniqid() . '.png';

                    // Store image
                    Storage::disk('public')->put($imageName, $imageDecoded);

                    // Update user's profile image
                    $userData['profile_image'] = $imageName;

                    // Optional: Delete old image if exists
                    if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
                        Storage::disk('public')->delete($student->profile_image);
                    }
                } catch (\Exception $imageUploadError) {
                    Log::error('Profile image upload failed: ' . $imageUploadError->getMessage());
                    // Optionally, you can choose to continue without updating the image
                    // Or you can throw the exception to rollback the entire transaction
                }
            }

            // Update user
            $student->update($userData);

            // Update medical information
            $student->studentMedicalInformation()->updateOrCreate(
                [],
                [
                    'allergies' => $request->allergies ?? null,
                    'notes' => $request->notes ?? null,
                    'medication' => $request->medication ?? null,
                ]
            );

            // Update school details
            $student->studentSchoolDetails()->updateOrCreate(
                [],
                [
                    'status' => $request->status
                ]
            );

            DB::commit();

            return redirect()->route('parent-student.index', ['id' => $id])
                ->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update student', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('parent-student.index', ['id' => $id])
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }
}
