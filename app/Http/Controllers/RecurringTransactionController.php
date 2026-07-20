<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurrings = RecurringTransaction::where('user_id', Auth::id())
            ->with(['account', 'category'])
            ->orderBy('next_run_date')
            ->get();

        return view('recurring.index', compact('recurrings'));
    }

    public function create()
    {
        $userId = Auth::id();
        $accounts = Account::where('user_id', $userId)->get();
        $categories = Category::availableFor($userId)->get();

        return view('recurring.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'account_id' => ['required', 'exists:accounts,id,user_id,' . $userId],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'day_of_month' => ['nullable', 'integer', 'between:1,31', 'required_if:frequency,monthly'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $validated['user_id'] = $userId;
        $validated['next_run_date'] = $validated['start_date'];
        unset($validated['start_date']);

        RecurringTransaction::create($validated);

        return redirect()->route('recurring.index')->with('success', 'Transaksi berulang berhasil dijadwalkan.');
    }

    public function edit(RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();
        $accounts = Account::where('user_id', $userId)->get();
        $categories = Category::availableFor($userId)->get();

        return view('recurring.edit', compact('recurring', 'accounts', 'categories'));
    }

    public function update(Request $request, RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $recurring->update($validated);

        return redirect()->route('recurring.index')->with('success', 'Transaksi berulang berhasil diperbarui.');
    }

    public function destroy(RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $recurring->delete();
        return redirect()->route('recurring.index')->with('success', 'Transaksi berulang berhasil dihapus.');
    }
}
