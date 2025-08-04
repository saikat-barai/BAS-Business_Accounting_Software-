<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function account()
    {
        $accounts = Account::orderBy('id', 'desc')->get();
        return view('backend.pages.account', compact('accounts'));
    }

    public function accountList()
    {
        $data = Account::orderBy('id', 'desc')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Account Found.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Account List.',
                'data' => $data,
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:bank,cash',
            'account_number' => 'nullable|string',
            'opening_balance' => 'required|numeric',
        ]);

        Account::create([
            'name' => $request->name,
            'type' => $request->type,
            'account_number' => $request->account_number,
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
        ]);

        return response()->json(['message' => 'Account created']);
    }

    public function destroy($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $account->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function accountById(Request $request)
    {
        $data = Account::where('id', $request->id)->first();
        if ($data !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Account Found Successfully.',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Account Not Found.',
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:bank,cash',
            'account_number' => 'nullable|string',
            'opening_balance' => 'required|numeric',
        ]);

        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $account->update([
            'name' => $request->name,
            'type' => $request->type,
            'account_number' => $request->account_number,
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
        ]);

        return response()->json(['message' => 'Account updated successfully']);
    }
}
