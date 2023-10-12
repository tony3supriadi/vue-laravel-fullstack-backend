<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $table = "post_likes";

    protected $fillable = [
        'parent_id',
        'post_id',
        'user_id',
        'liked'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
