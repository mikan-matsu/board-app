<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'board_id',
        'user_id',
        'name',        
        'content',
        'image_path',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
