<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class School extends Model
{
    protected $primaryKey = 'schoolID';

    protected $guarded = [];

    public function scopeByOrganisation(Builder $query, ?int $organisationId): Builder
    {
        if (!$organisationId) {
            return $query;
        }

        return $query->where('schools.schoolID', $organisationId);
    }

    public function scopeNotClosed(Builder $query): Builder
    {
        return $query->whereNull('closedDate');
    }

    public function users(): BelongsToMany
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(User::class,
            $database . '.userSchoolAccess',
            'userID',
            'schoolID'
        );
    }
}
