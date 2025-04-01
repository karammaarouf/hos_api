<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')
            ->with('department')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Doctors retrieved successfully',
            'data' => $doctors
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $doctor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'department_id' => $request->department_id,
            'role' => 'doctor'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Doctor created successfully',
            'data' => $doctor->load('department')
        ], 201);
    }

    public function show(string $id)
    {
        $doctor = User::where('role', 'doctor')
            ->with('department')
            ->find($id);

        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Doctor retrieved successfully',
            'data' => $doctor
        ]);
    }

    public function update(Request $request, string $id)
    {
        $doctor = User::where('role', 'doctor')->find($id);

        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'phone' => 'string',
            'address' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $doctor->update($request->only([
            'name', 'email', 'phone', 'address', 'department_id'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Doctor updated successfully',
            'data' => $doctor->load('department')
        ]);
    }

    public function destroy(string $id)
    {
        $doctor = User::where('role', 'doctor')->find($id);

        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        $doctor->delete();

        return response()->json([
            'status' => true,
            'message' => 'Doctor deleted successfully'
        ]);
    }
}
