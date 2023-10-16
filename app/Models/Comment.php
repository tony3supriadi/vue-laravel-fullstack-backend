<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'parent_id',
        'post_id',
        'user_id',
        'content'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function like()
    {
        return $this->hasOne(PostLike::class)->where('liked', true);
    }

    public function dislike()
    {
        return $this->hasOne(PostLike::class)->where('liked', false);
    }
}
