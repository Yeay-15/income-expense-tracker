<?php

namespace App\Models;

use App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = ['name', 'icon'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
