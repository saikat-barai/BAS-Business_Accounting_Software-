<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $accountIds = Account::whereIn('type', ['bank', 'cash'])->pluck('id');
        $totalIncome = Payment::whereIn('account_id', $accountIds)->sum('amount');

        $totalExpense = Expense::sum('amount');

        $profitOrLoss = $totalIncome - $totalExpense;
        
        $bankCashAccounts = Account::whereIn('type', ['bank', 'cash'])->sum('current_balance');

        return view('backend.dashboard.bashboard', compact('totalIncome', 'totalExpense', 'profitOrLoss', 'bankCashAccounts'));
    }
}
