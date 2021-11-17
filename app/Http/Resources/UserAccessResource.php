<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserAccessResource
 *
 * @package App\Http\Resources
 *
 * @OA\Schema(
 *    title="UserAccess"
 * )
 */
class UserAccessResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @OA\Property(
     *   property="hasAccess",
     *   type="boolean"
     * )
     *
     * @OA\Property(
     *   property="isEnabled",
     *   type="boolean"
     * )
     *
     * @OA\Property(
     *   property="productSpecificClaimsNamespace",
     *   type="string"
     * )
     *
     * @OA\Property(
     *   property="productSpecificClaims",
     *   type="object",
     *       @OA\Property(
     *           property="additionalProperty1",
     *           type="string"
     *       ),
     *       @OA\Property(
     *           property="additionalProperty2",
     *           type="string"
     *       ),
     *       @OA\Property(
     *           property="additionalProperty3",
     *           type="string"
     *       )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'hasAccess' => $this->schools_count === 1,
            'isEnabled' => $this->allowLogin === 1,
            'productSpecificClaimsNamespace' => 'foo', // TODO: What are these?
            'productSpecificClaims' => [],
        ];
    }
}
