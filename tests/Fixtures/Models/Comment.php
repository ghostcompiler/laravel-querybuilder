<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $table = 'comments';

    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
