<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with financial summary.
     */
    public function index()
    {
        $userId = Auth::id();

        // Calculate total income
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        // Calculate total expense
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        // Calculate current balance
        $balance = $totalIncome - $totalExpense;

        // Pass the calculated data to the view
        return view('dashboard', compact('totalIncome', 'totalExpense', 'balance'));
    }

    /**
     * Export transaction report to PDF.
     */
    public function exportPdf()
    {
        $userId = Auth::id();

        // Ambil semua transaksi user yang login
        $transactions = Transaction::where('user_id', $userId)->orderBy('date', 'desc')->get();

        // Hitung total
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Load view khusus PDF dan kirim data ke dalamnya
        $pdf = Pdf::loadView('pdf.report', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));

        // Unduh file PDF dengan nama tertentu
        return $pdf->download('Financial_Report.pdf');
    }
}
