<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SavingGoal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavingGoalController extends Controller
{
    public function index()
    {
        $goals = SavingGoal::where('user_id', Auth::id())
            ->withSum('transactions as saved_amount', 'amount')
            ->get()
            ->map(function ($goal) {
                $goal->saved_amount = $goal->saved_amount ?? 0;
                $goal->percentage = $goal->target_amount > 0
                    ? min(100, round(($goal->saved_amount / $goal->target_amount) * 100))
                    : 0;
                return $goal;
            });

        return view('saving_goals.index', compact('goals'));
    }

    public function create()
    {
        return view('saving_goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date|after:today',
        ]);

        $validated['user_id'] = Auth::id();
        SavingGoal::create($validated);

        return redirect()->route('saving-goals.index')->with('success', 'Target tabungan berhasil dibuat.');
    }

    public function edit(SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('saving_goals.edit', compact('savingGoal'));
    }

    public function update(Request $request, SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date',
        ]);

        $savingGoal->update($validated);

        return redirect()->route('saving-goals.index')->with('success', 'Target tabungan berhasil diperbarui.');
    }

    public function destroy(SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        if ($savingGoal->transactions()->exists()) {
            return redirect()->route('saving-goals.index')
                ->with('error', 'Tidak bisa menghapus target yang sudah punya alokasi dana. Tarik dulu dananya.');
        }

        $savingGoal->delete();
        return redirect()->route('saving-goals.index')->with('success', 'Target tabungan berhasil dihapus.');
    }

    /**
     * Form alokasi dana ke target tabungan.
     */
    public function allocateForm(SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $accounts = Account::where('user_id', Auth::id())->get();
        return view('saving_goals.allocate', compact('savingGoal', 'accounts'));
    }

    /**
     * Proses alokasi dana: catat 1 transaksi expense, category_id = NULL, saving_goal_id terisi.
     */
    public function allocate(Request $request, SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();

        $validated = $request->validate([
            'account_id' => ['required', 'exists:accounts,id,user_id,' . $userId],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated, $userId, $savingGoal) {
            Transaction::create([
                'user_id' => $userId,
                'account_id' => $validated['account_id'],
                'category_id' => null,
                'saving_goal_id' => $savingGoal->id,
                'type' => 'expense',
                'amount' => $validated['amount'],
                'description' => 'Alokasi tabungan: ' . $savingGoal->name,
                'date' => $validated['date'],
            ]);
        });

        return redirect()->route('saving-goals.index')->with('success', 'Dana berhasil dialokasikan ke target tabungan.');
    }
}
