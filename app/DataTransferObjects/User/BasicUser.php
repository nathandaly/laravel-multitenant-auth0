<?php

declare(strict_types=1);

namespace App\DataTransferObjects\User;

use App\Models\User;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * Class BasicUser
 *
 * @package App\DataTransferObjects\User
 *
 * @OA\Schema()
 */
#[strict]
final class BasicUser extends DataTransferObject
{
    /**
     * @OA\Property(
     *   property="fullName",
     *   type="string",
     *   description="User fullname"
     * )
     * @var string
     */
    public string $fullName;

    /**
     * @OA\Property(
     *   property="nickName",
     *   type="string",
     *   description="User nickname"
     * )
     * @var string
     */
    public string $nickName;

    /**
     * @throws UnknownProperties
     */
    public static function fromModel(User $user): BasicUser
    {
        return new BasicUser([
            'fullName' => $user->full_name,
            'nickName' => $user->nick_name,
        ]);
    }
}
