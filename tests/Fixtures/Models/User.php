<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models;

use GhostCompiler\LaravelQueryBuilder\Concerns\HasQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasQueryBuilder;
    use SoftDeletes;

    protected $table = 'users';

    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected array $searchable = [
        'name',
        'email',
        'profile.bio',
        'roles.name',
        'roles.permissions.name',
    ];

    protected array $sortable = [
        'id',
        'name',
        'created_at',
        'profile.city',
    ];

    protected array $selectable = [
        'id',
        'name',
        'email',
    ];

    protected array $filterable = [
        'status',
        'is_active',
        'score',
        'created_at',
        'roles.name',
        'profile.country',
    ];

    protected array $allowedFilterOperators = [
        'status' => ['=', 'in'],
        'is_active' => ['=', '!=', 'in', 'not_in'],
        'score' => ['=', '>=', 'between'],
        'roles.name' => ['='],
        'profile.country' => ['='],
        'is_high_score' => ['='],
    ];

    protected array $customFilters = [
        'is_high_score' => 'applyHighScoreFilter',
    ];

    protected array $allowedRelations = [
        'profile',
        'roles',
        'roles.permissions',
        'posts',
    ];

    protected int $defaultPerPage = 2;

    protected int $maxPerPage = 50;

    protected string $defaultSortBy = 'created_at';

    protected string $defaultSortDir = 'desc';

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    protected function applyHighScoreFilter($query, mixed $value): void
    {
        $enabled = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        if ($enabled === true) {
            $query->where('users.score', '>=', 90);

            return;
        }

        if ($enabled === false) {
            $query->where('users.score', '<', 90);
        }
    }
}
