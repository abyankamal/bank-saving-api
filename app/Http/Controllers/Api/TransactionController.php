<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Account $account)
    {
        $transactions = Transaction::where('account_id', $account->id)->with('account')->get();

        if ($transactions->isEmpty()) {
            return response()->json(['error' => 'Akun ini belum pernah melakukan transaksi'], 404);
        }

        return response()->json($transactions, 200);
    }
}
