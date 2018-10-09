<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';
    protected $fillable = [
        'author_name',
        'author_email',
        'comment'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }
}
