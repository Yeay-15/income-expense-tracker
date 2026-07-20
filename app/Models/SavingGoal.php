<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    protected $fillable = ['user_id', 'name', 'target_amount', 'target_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Perbaikan: Pisahkan perhitungan berdasarkan tipe transaksi
    public function getSavedAmountAttribute(): float
    {
        $allocated = $this->transactions()->where('type', 'expense')->sum('amount');
        $withdrawn = $this->transactions()->where('type', 'income')->sum('amount');

        return (float) ($allocated - $withdrawn);
    }
}
