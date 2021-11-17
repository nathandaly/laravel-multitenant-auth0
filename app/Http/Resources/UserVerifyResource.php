<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVerifyResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'credentialsValid' => $this->valid_credentials ?? false,
        ];
    }
}
