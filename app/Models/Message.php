<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false; // created_atのみを運用
    protected $fillable = ['user_id', 'body', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
