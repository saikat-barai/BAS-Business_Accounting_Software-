<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function expense()
    {
        return view('backend.pages.expense');
    }

    public function expenseList()
    {
        $data = Expense::orderBy('created_at', 'desc')->with('account')->with('category')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Expense Found.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Expense List.',
                'data' => $data,
            ], 200);
        }
    }
    public function store(Request $request)
    {
        //  Validate input
        $validated = $request->validate([
            'account_id'    => 'required|exists:accounts,id',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string|max:255',
            'amount'        => 'required|numeric|min:0.01',
            'date'          => 'required|date',
        ]);

        try {
            //  Handle receipt upload (optional)
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts', 'public');
            }

            //  Create expense
            $expense = Expense::create([
                'account_id'    => $validated['account_id'],
                'category_id'   => $validated['category_id'],
                'description'   => $validated['description'] ?? null,
                'amount'        => $validated['amount'],
                'date'          => $validated['date'],
                'receipt_path'  => $receiptPath,
            ]);

            //  Decrease account current_balance
            $account = Account::find($validated['account_id']);
            if ($account) {
                $account->decrement('current_balance', $validated['amount']);
            }

            //  Return response
            return response()->json([
                'status'  => true,
                'message' => 'Expense saved successfully.',
                'data'    => $expense,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to save expense.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $expense = Expense::with(['category', 'account'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data'   => $expense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Expense not found.',
                'error'   => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        //  Validate input
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'account_id'   => 'required|exists:accounts,id',
            'amount'       => 'required|numeric|min:0.01',
            'date'         => 'required|date',
            'description'  => 'nullable|string|max:255',
        ]);

        try {
            //  Find the expense
            $expense = Expense::findOrFail($id);

            //  Restore balance of old account
            $oldAccount = Account::find($expense->account_id);
            if ($oldAccount) {
                $oldAccount->increment('current_balance', $expense->amount);
            }

            //  Update expense
            $expense->update([
                'category_id'  => $validated['category_id'],
                'account_id'   => $validated['account_id'],
                'amount'       => $validated['amount'],
                'date'         => $validated['date'],
                'description'  => $validated['description'] ?? null,
            ]);

            //  Deduct new amount from new account
            $newAccount = Account::find($validated['account_id']);
            if ($newAccount) {
                $newAccount->decrement('current_balance', $validated['amount']);
            }

            // Return success response
            return response()->json([
                'status'  => true,
                'message' => 'Expense updated successfully.',
                'data'    => $expense,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update expense.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
