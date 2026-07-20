<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with financial summary.
     */
    public function index(Request $request)
    {
        $data = $this->getSummaryData($request);
        return view('dashboard', $data);
    }

    /**
     * Export transaction report to PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getSummaryData($request);

        // Tambahan khusus PDF: daftar detail transaksi bulan tersebut
        $data['transactions'] = Transaction::where('user_id', Auth::id())
            ->whereMonth('date', $data['month'])
            ->whereYear('date', $data['year'])
            ->orderBy('date', 'desc')
            ->with(['category', 'account'])
            ->get();

        $pdf = Pdf::loadView('pdf.report', $data);

        return $pdf->download('Laporan-Keuangan-' . $data['month'] . '-' . $data['year'] . '.pdf');
    }

    /**
     * Data untuk grafik interaktif (dipanggil via AJAX/fetch dari dashboard).
     */
    public function chartData(Request $request)
    {
        $userId = Auth::id();
        $range = $request->query('range', 'monthly'); // weekly | monthly | yearly

        $query = Transaction::where('user_id', $userId)->whereNull('transfer_group_id');

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        if ($range === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('D, d M');
                $incomeData[] = (clone $query)->where('type', 'income')->whereDate('date', $date)->sum('amount');
                $expenseData[] = (clone $query)->where('type', 'expense')->whereDate('date', $date)->sum('amount');
            }
        } elseif ($range === 'yearly') {
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = \Carbon\Carbon::create()->month($m)->translatedFormat('M');
                $incomeData[] = (clone $query)->where('type', 'income')->whereMonth('date', $m)->whereYear('date', now()->year)->sum('amount');
                $expenseData[] = (clone $query)->where('type', 'expense')->whereMonth('date', $m)->whereYear('date', now()->year)->sum('amount');
            }
        } else {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $weekIndex = 1;
            for ($date = $start->copy(); $date->lte($end); $date->addWeek()) {
                $weekEnd = $date->copy()->addDays(6)->min($end);
                $labels[] = 'Minggu ' . $weekIndex;
                $incomeData[] = (clone $query)->where('type', 'income')->whereBetween('date', [$date, $weekEnd])->sum('amount');
                $expenseData[] = (clone $query)->where('type', 'expense')->whereBetween('date', [$date, $weekEnd])->sum('amount');
                $weekIndex++;
            }
        }

        return response()->json([
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ]);
    }

    /**
     * Satu sumber kebenaran untuk ringkasan dashboard.
     * Dipakai oleh index() (tampilan web) dan exportPdf() (laporan PDF)
     * supaya keduanya selalu konsisten — tidak ada variabel yang lupa disertakan.
     */
    private function getSummaryData(Request $request): array
    {
        $userId = Auth::id();
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;
        $netWorth = Account::where('user_id', $userId)->get()->sum('balance');

        $budgetAlerts = Budget::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->with('category')
            ->get()
            ->map(function ($budget) {
                $spent = BudgetController::spentForCategory($budget->category_id, $budget->month, $budget->year);
                $budget->percentage = $budget->amount > 0 ? round(($spent / $budget->amount) * 100) : 0;
                return $budget;
            })
            ->filter(fn($b) => $b->percentage >= 80);

        return compact('totalIncome', 'totalExpense', 'balance', 'netWorth', 'budgetAlerts', 'month', 'year');
    }
}
