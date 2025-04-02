<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient', 'appointment'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Invoices retrieved successfully',
            'data' => $invoices
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $invoice = Invoice::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Invoice created successfully',
            'data' => $invoice->load(['patient', 'appointment'])
        ], 201);
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['patient', 'appointment'])->find($id);

        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Invoice retrieved successfully',
            'data' => $invoice
        ]);
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'exists:appointments,id',
            'patient_id' => 'exists:users,id',
            'amount' => 'numeric|min:0',
            'status' => 'in:pending,paid,cancelled',
            'description' => 'nullable|string',
            'due_date' => 'date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $invoice->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Invoice updated successfully',
            'data' => $invoice->load(['patient', 'appointment'])
        ]);
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        $invoice->delete();

        return response()->json([
            'status' => true,
            'message' => 'Invoice deleted successfully'
        ]);
    }
}