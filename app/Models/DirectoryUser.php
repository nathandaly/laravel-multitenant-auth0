<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class DirectoryUser
 *
 * @package App\Models
 *
 * @OA\Schema(
 *     title="DirectoryUser",
 *     description="Directory User model used for initial authentication and database context switching",
 * )
 *
 * @OA\Property(
 *     property="username",
 *     type="string",
 *     description="username",
 * )
 *
 * @OA\Property(
 *     property="password",
 *     type="string",
 *     description="password",
 * )
 */
class DirectoryUser extends Authenticatable
{
    use HasFactory, Notifiable;

    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected $appends = [
        'valid_credentials' => false,
    ];

    protected $table = 'usrs';

    protected $primaryKey = 'usrID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'pwd',
        'shards',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pwd',
        'token',
    ];

    public function getRouteKey(): string
    {
        return 'email';
    }

    public function getValidCredentialsAttribute(): bool
    {
        return $this->attributes['valid_credentials'] ?? false;
    }
}
