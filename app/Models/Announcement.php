<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['created_by', 'title', 'message', 'is_active'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
