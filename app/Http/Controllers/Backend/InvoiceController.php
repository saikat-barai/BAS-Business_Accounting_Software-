<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceController extends Controller
{
    public function invoice()
    {
        return view('backend.pages.invoice');
    }

    public function invoiceList()
    {
        $data = Invoice::orderBy('created_at', 'desc')->with('client')->with('items')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Invoice Found.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice List.',
                'data' => $data,
            ], 200);
        }
    }

    public function store(Request $request)
    {
        // Step 1: Validate input
        $validator = Validator::make($request->all(), [
            'client_id'      => 'required|exists:clients,id',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'description'    => 'required|string|max:255',
            'unit_price'     => 'required|numeric|min:0',
            'quantity'       => 'required|numeric|min:1',
            'subtotal'       => 'required|numeric|min:0',
            'tax'            => 'required|numeric|min:0',
            'discount'       => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $validated = $validator->validated();

            // Calculate subtotal, tax, discount, and total
            $calculatedSubtotal = $validated['unit_price'] * $validated['quantity'];
            $taxPercentage = $validated['tax'];
            $discount = $validated['discount'];

            $taxAmount = ($calculatedSubtotal * $taxPercentage) / 100;
            $total = ($calculatedSubtotal + $taxAmount) - $discount;


            // Step 3: Create the invoice
            $invoice = Invoice::create([
                'client_id'      => $validated['client_id'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_date'   => $validated['invoice_date'],
                'subtotal'       => $calculatedSubtotal,
                'tax'            => $taxAmount,
                'discount'       => $discount,
                'total'          => $total,
                'status'         => 'unpaid',
            ]);

            // Step 4: Create the invoice item
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $validated['description'],
                'quantity'    => $validated['quantity'],
                'unit_price'  => $validated['unit_price'],
                'total'       => $validated['unit_price'] * $validated['quantity'],
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Invoice created successfully.',
                'data'    => $invoice->load('items'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Invoice creation failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::find($id);

            if (!$invoice) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invoice not found.',
                ], 404);
            }

            $invoice->items()->delete();

            $invoice->delete();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Invoice deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Invoice deletion failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
