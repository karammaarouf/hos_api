<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    public function index($patientId)
    {
        try {
            $records = MedicalRecord::where('patient_id', $patientId)
                                   ->with('patient')
                                   ->get();

            return response()->json([
                'status' => true,
                'message' => 'Medical records retrieved successfully',
                'data' => $records
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve medical records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:users,id',
                'diagnosis' => 'nullable|string',
                'treatment' => 'nullable|string',
                'medications' => 'nullable|string',
                'allergies' => 'nullable|string',
                'medical_history' => 'nullable|string',
                'surgical_history' => 'nullable|string',
                'family_history' => 'nullable|string',
                'lab_results' => 'nullable|string',
                'vital_signs' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $record = MedicalRecord::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Medical record created successfully',
                'data' => $record
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create medical record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $record = MedicalRecord::with('patient')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Medical record retrieved successfully',
                'data' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Medical record not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $record = MedicalRecord::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'diagnosis' => 'nullable|string',
                'treatment' => 'nullable|string',
                'medications' => 'nullable|string',
                'allergies' => 'nullable|string',
                'medical_history' => 'nullable|string',
                'surgical_history' => 'nullable|string',
                'family_history' => 'nullable|string',
                'lab_results' => 'nullable|string',
                'vital_signs' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $record->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Medical record updated successfully',
                'data' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update medical record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $record = MedicalRecord::findOrFail($id);
            $record->delete();

            return response()->json([
                'status' => true,
                'message' => 'Medical record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete medical record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}