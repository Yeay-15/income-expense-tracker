<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $month = request('month', now()->month);
        $year  = request('year', now()->year);

        $budgets = Budget::where('user_id', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->with('category')
            ->get()
            ->map(function ($budget) {
                $budget->spent = $this->spentForCategory($budget->category_id, $budget->month, $budget->year);
                $budget->percentage = $budget->amount > 0
                    ? min(100, round(($budget->spent / $budget->amount) * 100))
                    : 0;
                return $budget;
            });

        return view('budgets.index', compact('budgets', 'month', 'year'));
    }

    public function create()
    {
        $categories = Category::availableFor(Auth::id())->where('type', 'expense')->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $validated['user_id'] = Auth::id();

        // Cegah duplikasi budget untuk kategori+bulan yang sama (selaras dengan unique constraint di migration)
        $exists = Budget::where('user_id', Auth::id())
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['category_id' => 'Budget untuk kategori dan bulan ini sudah ada.']);
        }

        Budget::create($validated);

        return redirect()->route('budgets.index', ['month' => $validated['month'], 'year' => $validated['year']])
            ->with('success', 'Budget berhasil dibuat.');
    }

    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::availableFor(Auth::id())->where('type', 'expense')->get();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Kategori/bulan/tahun sengaja tidak diizinkan diubah lewat edit,
        // supaya histori budget bulan lalu tidak "tergeser" ke bulan lain.
        $budget->update($validated);

        return redirect()->route('budgets.index', ['month' => $budget->month, 'year' => $budget->year])
            ->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        [$month, $year] = [$budget->month, $budget->year];
        $budget->delete();

        return redirect()->route('budgets.index', compact('month', 'year'))
            ->with('success', 'Budget berhasil dihapus.');
    }

    /**
     * Hitung total pengeluaran user untuk kategori tertentu di bulan/tahun tertentu.
     * Transfer aman diabaikan otomatis karena category_id-nya NULL.
     */
    public static function spentForCategory(int $categoryId, int $month, int $year): float
    {
        return \App\Models\Transaction::where('user_id', Auth::id())
            ->where('category_id', $categoryId)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    private function spentForCategory2($categoryId, $month, $year)
    {
        return self::spentForCategory($categoryId, $month, $year);
    }
}
