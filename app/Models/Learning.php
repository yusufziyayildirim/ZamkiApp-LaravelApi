<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Learning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lang',
        'level',
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }
}
