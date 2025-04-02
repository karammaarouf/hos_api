<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'patient')->get();
        return response()->json([
            'status' => true,
            'message' => 'Patients retrieved successfully',
            'data' => $patients
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'patient'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Patient created successfully',
            'data' => $patient
        ], 201);
    }

    public function show(string $id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Patient retrieved successfully',
            'data' => $patient
        ]);
    }

    public function update(Request $request, string $id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'phone' => 'string',
            'address' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient->update($request->only([
            'name', 'email', 'phone', 'address'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Patient updated successfully',
            'data' => $patient
        ]);
    }

    public function destroy(string $id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        try {
            // Force delete the patient and all related records will be handled by the database constraints
            $patient->forceDelete();

            return response()->json([
                'status' => true,
                'message' => 'Patient deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
