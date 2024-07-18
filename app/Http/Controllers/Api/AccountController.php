<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

$formattedDate = Carbon::now()->format('d/m/y');

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $userid = $request->user()->id;

        $accounts = Account::where('user_id', $userid)
            ->with('depositoType') // Ensure this matches the relationship method in the Account model
            ->get();

        return response()->json([
            'accounts' => $accounts->map(function ($account) {
                return [
                    'id' => $account->id,
                    'balance' => $account->balance,
                    'depositoType' => [
                        'id' => $account->depositoType->id,
                        'name' => $account->depositoType->name,
                        'yearly_return' => $account->depositoType->yearly_return,
                    ],
                    'created_at' => $account->created_at,
                ];
            })
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'deposito_type_id' => 'required|exists:deposito_types,id',
            'balance' => 'required|numeric|min:0',
        ]);
        $account = Account::create($request->all());
        return response()->json($account, 201);
    }
    public function deposit(Request $request, Account $account)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500000',
        ]);

        $account->balance += $request->amount;
        $account->save();

        $formattedDate = Carbon::now()->format('Y-m-d');

        Transaction::create([
            'account_id' => $account->id,
            'transaction_type' => 'deposit',
            'amount' => $request->amount,
            'transaction_date' => $formattedDate,
        ]);

        return response()->json($account, 201);
    }

    public function withdraw(Request $request, Account $account)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        if ($account->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        $startDate = $account->transactions()->where('transaction_type', 'deposit')->latest('transaction_date')->first()->transaction_date;
        $endDate = Carbon::parse($request->transaction_date);
        $monthsDiff = Carbon::parse($startDate)->diffInMonths($endDate);

        $monthlyReturn = $account->depositoType->yearly_return / 12 / 100;
        $endingBalance = $account->balance * pow(1 + $monthlyReturn, $monthsDiff);

        if ($endingBalance < $request->amount) {
            return response()->json(['error' => 'Insufficient funds after interest calculation'], 400);
        }

        $account->balance = $endingBalance - $request->amount;
        $account->save();

        $formattedDate = Carbon::now()->format('Y-m-d');

        Transaction::create([
            'account_id' => $account->id,
            'transaction_type' => 'withdrawal',
            'amount' => $request->amount,
            'transaction_date' => $formattedDate,
        ]);

        return response()->json([
            'account' => $account,
            'ending_balance' => $endingBalance,
            'withdrawn_amount' => $request->amount,
            'remaining_balance' => $account->balance,
            'transaction_type' => 'withdrawal',
        ], 200);
    }

    public function transaction(Account $account)
    {
        $transactions = Transaction::where('account_id', $account->id)->with('account')->get();

        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Akun ini belum pernah melakukan transaksi'], 200);
        }

        return response()->json($transactions, 200);
    }
}
