<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
{
    public function index()
    {
        $medicines = Pharmacy::all();
        return response()->json([
            'status' => true,
            'message' => 'Medicines retrieved successfully',
            'data' => $medicines
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'manufacturer' => 'required|string',
            'expiry_date' => 'required|date|after:today',
            'requires_prescription' => 'boolean',
            'status' => 'required|in:active,out-of-stock,discontinued'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicine = Pharmacy::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Medicine added successfully',
            'data' => $medicine
        ], 201);
    }

    public function show(string $id)
    {
        $medicine = Pharmacy::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => false,
                'message' => 'Medicine not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Medicine retrieved successfully',
            'data' => $medicine
        ]);
    }

    public function update(Request $request, string $id)
    {
        $medicine = Pharmacy::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => false,
                'message' => 'Medicine not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'generic_name' => 'string|max:255',
            'category' => 'string',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'manufacturer' => 'string',
            'expiry_date' => 'date|after:today',
            'requires_prescription' => 'boolean',
            'status' => 'in:active,out-of-stock,discontinued'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicine->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Medicine updated successfully',
            'data' => $medicine
        ]);
    }

    public function destroy(string $id)
    {
        $medicine = Pharmacy::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => false,
                'message' => 'Medicine not found'
            ], 404);
        }

        $medicine->delete();

        return response()->json([
            'status' => true,
            'message' => 'Medicine deleted successfully'
        ]);
    }
}