<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\DirectoryUser;
use App\Concerns\DatabaseContext;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Resources\UserVerifyResource;
use App\Http\Resources\UserAccessResource;
use App\DataTransferObjects\User\BasicUser;
use App\Http\Requests\User\UserAccessRequest;
use App\Http\Requests\User\UserVerifyRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class Users extends Controller
{
    use DatabaseContext;

    /**
     * @OA\Post(
     *      path="/api/v1/users/verify",
     *      operationId="verifyUser",
     *      tags={"User"},
     *      summary="Verify a users existence",
     *      description="Verify a user exists and the credentials match.",
     *     @OA\RequestBody(
     *         description="Verify user object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DirectoryUser")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function verify(UserVerifyRequest $request): UserVerifyResource
    {
        $hashToCheck = $request->input('username')
            . $request->input('password')
            . config('auth.password_salt');

        $directoryUser = DirectoryUser::whereUsername($request->input('username'))
            ->wherePwd(md5($hashToCheck))
            ->first();

        if ($directoryUser) {
            $directoryUser->valid_credentials = true;
        }

        return new UserVerifyResource($directoryUser);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/users/{email}",
     *      operationId="getUserByIdentifier",
     *      tags={"User"},
     *      summary="Get user name and nick from the identifier.",
     *      description="Returns a JSON object that has the full name and nickname of the user requested.",
     *      @OA\Parameter(
     *          name="email",
     *          description="primary email",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BasicUser"),
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     * @throws UnknownProperties
     */
    public function show(UserGetRequest $request, DirectoryUser $directoryUser): JsonResponse
    {
        $this->switchDbContext(strtolower($directoryUser->shards));
        $user = User::whereUsername($directoryUser->username)
            ->where('allowLogin', 1)
            ->firstOrFail();

        return new JsonResponse(BasicUser::fromModel($user));
    }

    /**
     * @OA\Get(
     *      path="api/v1/users/{email}/accessControl/{organisationId}",
     *      operationId="accessControl",
     *      tags={"User"},
     *      summary="Check if identifier has access to an organisation.",
     *      description="Returns a JSON object with confirmation of user access to an organisation and claims.",
     *      @OA\Parameter(
     *          name="email",
     *          description="primary email",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="organisationId",
     *          description="organisation ID",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="int"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserAccessResource"),
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function accessControl(UserAccessRequest $request, DirectoryUser $directoryUser): UserAccessResource
    {
        $this->switchDbContext(strtolower($directoryUser->shards));
        $organisationId = (int) $request->route('organisationId');

        // TODO: What do we need to check on schoolUserGrants and userGrants tables?
        // TODO: Do we take into account schoolID on the users table?

        $user = User::withCount(['schools' => function ($builder) use ($organisationId) {
            $builder->byOrganisation($organisationId)->notClosed();
        }])->whereUsername($directoryUser->username)
            ->firstOrFail();

        return new UserAccessResource($user);
    }
}
