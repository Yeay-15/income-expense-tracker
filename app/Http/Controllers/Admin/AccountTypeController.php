<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index()
    {
        $accountTypes = AccountType::withCount('accounts')->orderBy('name')->get();
        return view('admin.account-types.index', compact('accountTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:account_types,name',
        ]);

        AccountType::create($validated);

        return back()->with('success', 'Jenis akun berhasil ditambahkan.');
    }

    public function destroy(AccountType $accountType)
    {
        if ($accountType->accounts()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus jenis akun yang masih dipakai user.');
        }

        $accountType->delete();
        return back()->with('success', 'Jenis akun berhasil dihapus.');
    }
}
