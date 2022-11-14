<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NativeIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lang',
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }
}
