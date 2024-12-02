<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalInformation;
use Illuminate\Http\Request;

class StudentMedicalInformationController extends Controller
{
    public function index()
    {
        return StudentMedicalInformation::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'medication' => 'nullable|string',
        ]);

        return StudentMedicalInformation::create($validated);
    }

    public function show(StudentMedicalInformation $studentMedicalInformation)
    {
        return $studentMedicalInformation;
    }

    public function update(Request $request, StudentMedicalInformation $studentMedicalInformation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'medication' => 'nullable|string',
        ]);

        $studentMedicalInformation->update($validated);

        return $studentMedicalInformation;
    }

    public function destroy(StudentMedicalInformation $studentMedicalInformation)
    {
        $studentMedicalInformation->delete();

        return response()->noContent();
    }
}
