<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Appointments retrieved successfully',
            'data' => $appointments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after:today',
            'time' => 'required',
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment = Appointment::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Appointment created successfully',
            'data' => 'ok'], 201);
    }

    public function show(string $id)
    {
        $appointment = Appointment::with(['doctor', 'patient'])->find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Appointment retrieved successfully',
            'data' => $appointment
        ]);
    }

    public function update(Request $request, string $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'date|after:today',
            'time' => 'string',
            'doctor_id' => 'exists:users,id',
            'patient_id' => 'exists:users,id',
            'status' => 'in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Appointment updated successfully',
            'data' => $appointment->load(['doctor', 'patient'])
        ]);
    }

    public function destroy(string $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
