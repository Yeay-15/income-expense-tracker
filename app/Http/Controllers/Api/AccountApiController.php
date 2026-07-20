<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountApiController extends Controller
{
    // GET: Ambil semua daftar akun
    public function index()
    {
        $accounts = Account::with('accountType')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar akun berhasil diambil',
            'data'    => $accounts
        ], 200);
    }

    // POST: Tambah akun baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|exists:users,id',
            'account_type_id' => 'required|exists:account_types,id',
            'name'            => 'required|string|max:255',
            'initial_balance' => 'required|numeric',
        ]);

        $account = Account::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil ditambahkan',
            'data'    => $account
        ], 201);
    }
}
