<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function payment()
    {
        return view('backend.pages.payment');
    }

    public function paymentList()
    {
        $data = Payment::orderBy('created_at', 'desc')->with('account')->with('invoice')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Payment Found.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment List.',
                'data' => $data,
            ], 200);
        }
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'account_id' => 'required|exists:accounts,id',
            'amount'     => 'required|numeric|min:0.01',
            'date'       => 'required|date',
            'notes'      => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            //  Retrieve necessary models
            $invoice = Invoice::findOrFail($request->invoice_id);
            $account = Account::findOrFail($request->account_id);
            $amount  = round($request->amount, 2); // Precision fix

            $due = round($invoice->total - $invoice->paid_amount, 2);

            //  Check if invoice is already fully paid
            if ($due <= 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This invoice is already fully paid.',
                ], 400);
            }

            //  Prevent overpayment
            if ($amount > $due) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment exceeds the due amount.',
                ], 400);
            }

            //  Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'account_id' => $account->id,
                'amount'     => $amount,
                'date'       => $request->date,
                'notes'      => $request->notes,
            ]);

            //  Update account balance
            $account->increment('current_balance', $amount);

            //  Update invoice payment status
            $invoice->paid_amount += $amount;
            $invoice->status = match (true) {
                $invoice->paid_amount >= $invoice->total => 'paid',
                $invoice->paid_amount > 0 => 'partially_paid',
                default => 'unpaid',
            };
            $invoice->save();

            return response()->json([
                'status'  => true,
                'message' => 'Payment saved successfully.',
                'data'    => $payment,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



    public function destroy($id)
    {
        $account = Payment::find($id);

        if (!$account) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $account->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
