<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $primaryKey = 'userID';

    protected $guarded = [];

    public function getFullNameAttribute(): string
    {
        return sprintf('%s %s',
            $this->firstname,
            $this->surname
        );
    }

    public function getNickNameAttribute(): string
    {
        return sprintf('%s %s',
            $this->preferredFirstname,
            $this->preferredSurname
        );
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class,
            'userSchoolAccess',
            'userID',
            'schoolID'
        );
    }
}
