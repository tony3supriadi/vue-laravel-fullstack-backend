<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'author',
        'published_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class)->where('liked', true);
    }

    public function like_counts()
    {
        return $this->likes()->count();
    }

    public function dislikes()
    {
        return $this->hasMany(PostLike::class)->where('liked', false);
    }

    public function dislike_counts()
    {
        return $this->dislikes()->count();
    }
}
