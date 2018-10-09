<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Podcast extends Model
{
    use SoftDeletes;

    protected $table = 'podcasts';
    protected $fillable = [
        'name',
        'description',
        'marketing_url',
        'feed_url',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeForReview($query)
    {
        return $query->where('status', 'review');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
