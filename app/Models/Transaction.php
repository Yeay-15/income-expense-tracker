<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'saving_goal_id',
        'recurring_transaction_id',
        'transfer_group_id',
        'type',
        'amount',
        'description',
        'receipt_path',
        'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function savingGoal()
    {
        return $this->belongsTo(SavingGoal::class);
    }
    public function recurringTransaction()
    {
        return $this->belongsTo(RecurringTransaction::class);
    }
    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
