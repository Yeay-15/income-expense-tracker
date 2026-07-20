<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavingGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingGoalApiController extends Controller
{
    // GET: Ambil daftar target tabungan dan hitung saldo real-time
    public function index()
    {
        $goals = SavingGoal::with('transactions')->get()->map(function ($goal) {
            $allocated = $goal->transactions->where('type', 'expense')->sum('amount');
            $withdrawn = $goal->transactions->where('type', 'income')->sum('amount');

            $goal->saved_amount = $allocated - $withdrawn;
            $goal->percentage = $goal->target_amount > 0
                ? min(100, round(($goal->saved_amount / $goal->target_amount) * 100))
                : 0;

            return $goal;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar target tabungan berhasil diambil',
            'data'    => $goals
        ], 200);
    }

    // POST: Buat target tabungan baru via API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'name'          => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date'   => 'nullable|date',
        ]);

        $goal = SavingGoal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Target tabungan berhasil dibuat',
            'data'    => $goal
        ], 201);
    }
}
