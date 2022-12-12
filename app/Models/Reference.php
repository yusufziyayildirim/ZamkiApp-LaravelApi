<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'to_user_id', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo('App\Models\User', 'from_user_id', 'id');
    }
}
