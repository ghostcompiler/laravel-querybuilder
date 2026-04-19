<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models;

use GhostCompiler\LaravelQueryBuilder\Concerns\HasQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenUser extends Model
{
    use HasQueryBuilder;
    use SoftDeletes;

    protected $table = 'users';

    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected string $defaultSortBy = 'id';

    protected string $defaultSortDir = 'asc';

    protected array $dateFilterable = [
        'created_at',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
