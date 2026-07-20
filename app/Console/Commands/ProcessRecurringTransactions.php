<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'transactions:process-recurring';
    protected $description = 'Generate transactions from active recurring transaction templates that are due today.';

    public function handle(): int
    {
        $today = Carbon::today();

        $due = RecurringTransaction::where('is_active', true)
            ->whereDate('next_run_date', '<=', $today)
            ->get();

        $this->info("Ditemukan {$due->count()} transaksi berulang yang jatuh tempo.");

        foreach ($due as $recurring) {
            DB::transaction(function () use ($recurring) {
                Transaction::create([
                    'user_id' => $recurring->user_id,
                    'account_id' => $recurring->account_id,
                    'category_id' => $recurring->category_id,
                    'recurring_transaction_id' => $recurring->id,
                    'type' => $recurring->type,
                    'amount' => $recurring->amount,
                    'description' => $recurring->description,
                    'date' => $recurring->next_run_date,
                ]);

                $recurring->update([
                    'last_run_date' => $recurring->next_run_date,
                    'next_run_date' => $this->calculateNextRunDate($recurring),
                ]);
            });

            $this->line("- Diproses: {$recurring->description} (User #{$recurring->user_id})");
        }

        return self::SUCCESS;
    }

    private function calculateNextRunDate(RecurringTransaction $recurring): Carbon
    {
        $current = Carbon::parse($recurring->next_run_date);

        return match ($recurring->frequency) {
            'daily' => $current->addDay(),
            'weekly' => $current->addWeek(),
            'monthly' => $current->addMonthNoOverflow(), // hindari bug tanggal 31 jadi Maret
            'yearly' => $current->addYear(),
        };
    }
}
