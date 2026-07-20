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
        // 1. Ambil semua target tabungan milik user
        $goals = SavingGoal::where('user_id', Auth::id())->get();

        // 2. Hitung saldo riil satu per satu agar sangat akurat
        foreach ($goals as $goal) {
            $totalAllocated = $goal->transactions()->where('type', 'expense')->sum('amount');
            $totalWithdrawn = $goal->transactions()->where('type', 'income')->sum('amount');

            // Saldo = Alokasi dikurangi Penarikan
            $goal->saved_amount = $totalAllocated - $totalWithdrawn;

            // Hitung persentase (pastikan tidak minus dan maksimal 100%)
            if ($goal->target_amount > 0) {
                $percentage = round(($goal->saved_amount / $goal->target_amount) * 100);
                $goal->percentage = max(0, min(100, $percentage));
            } else {
                $goal->percentage = 0;
            }
        }

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

        // 1. Hitung sisa saldo riil saat ini
        $totalAllocated = $savingGoal->transactions()->where('type', 'expense')->sum('amount');
        $totalWithdrawn = $savingGoal->transactions()->where('type', 'income')->sum('amount');
        $savedAmount = $totalAllocated - $totalWithdrawn;

        // 2. Blokir HANYA JIKA masih ada sisa uang di dalam tabungan
        if ($savedAmount > 0) {
            return redirect()->route('saving-goals.index')
                ->with('error', 'Tidak bisa menghapus target. Tarik dulu sisa dananya (Rp ' . number_format($savedAmount, 0, ',', '.') . ').');
        }

        // 3. Jika saldo sudah 0 (atau tidak pernah diisi), izinkan penghapusan
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

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date'
        ]);

        // Cari kategori default 'Tabungan' (Expense). Jika tidak ada, otomatis buat baru.
        $category = \App\Models\Category::firstOrCreate([
            'name' => 'Tabungan',
            'type' => 'expense',
            'user_id' => null, // null berarti ini kategori default/global
        ]);

        // Buat transaksi alokasi
        \App\Models\Transaction::create([
            'user_id' => Auth::id(),
            'account_id' => $request->account_id,
            'category_id' => $category->id, // Masukkan ID kategori yang dicari/dibuat di atas
            'saving_goal_id' => $savingGoal->id,
            'type' => 'expense',
            'amount' => $request->amount,
            'description' => 'Alokasi tabungan: ' . $savingGoal->name,
            'date' => $request->date,
        ]);

        return redirect()->route('saving-goals.index')->with('success', 'Dana berhasil dialokasikan ke target tabungan.');
    }

    public function withdrawForm(SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) abort(403);

        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();

        // Hitung saldo tabungan saat ini untuk batasan penarikan
        $totalAllocated = $savingGoal->transactions()->where('type', 'expense')->sum('amount');
        $totalWithdrawn = $savingGoal->transactions()->where('type', 'income')->sum('amount');
        $savedAmount = $totalAllocated - $totalWithdrawn;

        return view('saving_goals.withdraw', compact('savingGoal', 'accounts', 'savedAmount'));
    }

    public function withdraw(Request $request, SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== Auth::id()) abort(403);

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date'
        ]);

        $totalAllocated = $savingGoal->transactions()->where('type', 'expense')->sum('amount');
        $totalWithdrawn = $savingGoal->transactions()->where('type', 'income')->sum('amount');
        $savedAmount = $totalAllocated - $totalWithdrawn;

        // Cegah penarikan melebihi saldo tabungan
        if ($request->amount > $savedAmount) {
            return back()->withErrors(['amount' => 'Jumlah penarikan melebihi saldo tabungan saat ini (Rp ' . number_format($savedAmount, 0, ',', '.') . ').']);
        }

        // Buat kategori default 'Pencairan Tabungan' (Income)
        $category = \App\Models\Category::firstOrCreate([
            'name' => 'Pencairan Tabungan',
            'type' => 'income',
            'user_id' => null,
        ]);

        \App\Models\Transaction::create([
            'user_id' => Auth::id(),
            'account_id' => $request->account_id,
            'category_id' => $category->id,
            'saving_goal_id' => $savingGoal->id,
            'type' => 'income', // Bertindak sebagai pemasukan ke akun
            'amount' => $request->amount,
            'description' => 'Tarik dana tabungan: ' . $savingGoal->name,
            'date' => $request->date,
        ]);

        return redirect()->route('saving-goals.index')->with('success', 'Dana tabungan berhasil ditarik ke akun Anda.');
    }
}
