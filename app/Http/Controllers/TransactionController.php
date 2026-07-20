<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with(['account', 'category'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        [$accounts, $categories] = $this->formOptions();
        return view('transactions.create', compact('accounts', 'categories'));
    }

    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }
        unset($validated['receipt']);

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->transfer_group_id) {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi transfer tidak bisa diedit. Hapus dan buat ulang jika perlu koreksi.');
        }

        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        [$accounts, $categories] = $this->formOptions();
        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {

        if ($transaction->transfer_group_id) {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi transfer tidak bisa diedit. Hapus dan buat ulang jika perlu koreksi.');
        }

        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($request->hasFile('receipt')) {
            // Hapus struk lama supaya storage tidak menumpuk file yatim
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }
        unset($validated['receipt']);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->transfer_group_id) {
            // Hapus kedua baris transfer sekaligus supaya saldo tetap konsisten
            Transaction::where('transfer_group_id', $transaction->transfer_group_id)
                ->where('user_id', Auth::id())
                ->get()
                ->each(function ($t) {
                    if ($t->receipt_path) {
                        Storage::disk('public')->delete($t->receipt_path);
                    }
                    $t->delete();
                });

            return redirect()->route('transactions.index')->with('success', 'Transfer berhasil dihapus.');
        }

        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Ambil daftar akun & kategori milik user untuk dropdown form.
     */
    private function formOptions(): array
    {
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)->get();

        // Perubahan: Urutkan "Lainnya" agar selalu di bawah, sisanya sesuai abjad
        $categories = Category::availableFor($userId)
            ->orderByRaw("CASE WHEN name = 'Lainnya' THEN 1 ELSE 0 END")
            ->orderBy('name', 'asc')
            ->get();

        return [$accounts, $categories];
    }
}
