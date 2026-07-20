<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionApiController extends Controller
{
    // 1. GET: Mengambil semua daftar transaksi
    public function index()
    {
        $transactions = Transaction::with(['account', 'category', 'savingGoal'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data'    => $transactions
        ], 200);
    }

    // 2. POST: Menambah transaksi baru via API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'type'       => 'required|in:income,expense',
            'amount'     => 'required|numeric|min:0.01',
            'date'       => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data'    => $transaction
        ], 201);
    }

    // 3. DELETE: Menghapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus'
        ], 200);
    }
}
