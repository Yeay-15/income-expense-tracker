<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('transfers.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'from_account_id' => ['required', 'different:to_account_id', 'exists:accounts,id,user_id,' . $userId],
            'to_account_id'   => ['required', 'exists:accounts,id,user_id,' . $userId],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'description'     => ['nullable', 'string', 'max:255'],
            'date'            => ['required', 'date'],
        ]);

        $fromAccount = Account::findOrFail($validated['from_account_id']);
        $toAccount   = Account::findOrFail($validated['to_account_id']);
        $description = $validated['description'] ?: "Transfer {$fromAccount->name} → {$toAccount->name}";
        $transferGroupId = (string) Str::uuid();

        DB::transaction(function () use ($validated, $userId, $fromAccount, $toAccount, $description, $transferGroupId) {
            // Baris 1: keluar dari akun asal
            Transaction::create([
                'user_id'           => $userId,
                'account_id'        => $fromAccount->id,
                'category_id'       => null,
                'type'              => 'expense',
                'amount'            => $validated['amount'],
                'description'       => $description,
                'date'              => $validated['date'],
                'transfer_group_id' => $transferGroupId,
            ]);

            // Baris 2: masuk ke akun tujuan
            Transaction::create([
                'user_id'           => $userId,
                'account_id'        => $toAccount->id,
                'category_id'       => null,
                'type'              => 'income',
                'amount'            => $validated['amount'],
                'description'       => $description,
                'date'              => $validated['date'],
                'transfer_group_id' => $transferGroupId,
            ]);
        });

        return redirect()->route('transactions.index')->with('success', 'Transfer berhasil dilakukan.');
    }
}
