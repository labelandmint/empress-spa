<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankDetail;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use PDF;
// use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        // Total Subscriptions (assuming 'user_role' = 2 is for subscribers)
        $subscriberCount = User::where('user_role', 2)->count();

        // Total Subscriptions Value
        $subscriptionValue = Transaction::where('status', 1)->sum('amount');

        // Total This Month
        $totalThisMonth = Transaction::where('status', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Total Last Month
        $totalLastMonth = Transaction::where('status', 1)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $transactions = Transaction::join('users', 'users.id', 'transactions.member_id')
            ->join('subscription_plans', 'subscription_plans.id', 'transactions.subscription_plan_id')
            ->select('transactions.*', 'users.f_name', 'users.l_name', 'subscription_plans.title')
            ->get();
            
            $title='Transactions';

        // Return view with the calculated values
        return view('admin.transactions.index', compact('title','subscriberCount', 'subscriptionValue', 'totalThisMonth', 'totalLastMonth', 'transactions'));
    }

    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }

    public function exportPDF()
    {
        $transactions = Transaction::join('users', 'users.id', 'transactions.member_id')
            ->join('subscription_plans', 'subscription_plans.id', 'transactions.subscription_plan_id')
            ->select('transactions.*', 'users.f_name', 'users.l_name', 'subscription_plans.title')
            ->get();

        $setting = Setting::first();
        $subsCount = User::where('user_role', 2)->count();
        $subsCountThisMonth = User::where('user_role', 2)
            ->whereMonth('created_at', date('m'))
            ->count();
        $subsCountLastMonth = User::where('user_role', 2)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $subsAmount = Transaction::where('status', 1)->sum('amount');

        // return view('admin.transactions.pdf', compact('transactions'));
        $pdf = PDF::loadView('admin.transactions.pdf', compact('transactions', 'setting', 'subsCount', 'subsCountThisMonth', 'subsAmount', 'subsCountLastMonth'));
        $pdf->setOptions(['defaultFont' => 'Poppins']);
        return $pdf->download('transactions.pdf');
    }

    public function downloadInvoice(Request $request, $id)
    {
        $transaction = Transaction::join('users', 'users.id', 'transactions.member_id')
            ->join('subscription_plans', 'subscription_plans.id', 'transactions.subscription_plan_id')
            ->select('transactions.amount', 'transactions.created_at', 'users.id as user_id', 'users.f_name', 'users.l_name', 'users.email', 'subscription_plans.title', 'subscription_plans.description')
            ->where('transactions.id', $id)
            ->first();
        $BankDetail = [];
        if ($transaction) {
            $BankDetail = BankDetail::where('user_id', $transaction->user_id)->first();
        }
        $setting = Setting::first();


        // Load the PDF view with the transactions and logo data
        $invoice = PDF::loadView('admin.transactions.invoice', compact('transaction', 'BankDetail', 'setting'));
        $invoice->setOptions(['defaultFont' => 'Poppins']);

        // Download the invoice PDF
        return $invoice->download('invoice.pdf');
    }
}
