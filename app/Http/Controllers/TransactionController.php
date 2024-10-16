<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankDetail;
use Auth;
use PDF;

class TransactionController extends Controller
{
    //
    public function index(){
        $transactions = Transaction::join('users','users.id','transactions.member_id')
        ->join('subscription_plans','subscription_plans.id','transactions.subscription_plan_id')
        ->where('member_id',Auth::user()->id)
        ->select('transactions.*','users.f_name','users.l_name','subscription_plans.title' )
        ->get();
        // return $transactions;
        return view('transactions.index',compact('transactions'));
    }

    public function downloadInvoice(Request $request,$id)
    {
        $transaction = Transaction::join('users', 'users.id', 'transactions.member_id')
            ->join('subscription_plans','subscription_plans.id','transactions.subscription_plan_id')
            ->select('transactions.amount', 'transactions.created_at', 'users.f_name','users.l_name','subscription_plans.title','subscription_plans.description')
            ->where('transactions.id',$id)
            ->where('transactions.member_id',Auth::user()->id)
            ->first();

        $BankDetail = BankDetail::where('user_id',Auth::user()->id)->first();
        // Load the PDF view with the transactions and logo data
        $invoice = PDF::loadView('transactions.invoice', compact('transaction','BankDetail'));
        $invoice->setOptions(['defaultFont' => 'Poppins']);

        // Download the invoice PDF
        return $invoice->download('invoice.pdf');

    }
}
