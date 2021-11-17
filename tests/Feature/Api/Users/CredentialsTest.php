<?php

namespace Tests\Feature\Api\Users;

use Illuminate\Http\JsonResponse;

use function Pest\Faker\faker;

test('User with correct credentials receives user details')
    ->get('/api/v1/users/nathan.daly@junipereducation.org')
    ->assertOk()
    ->assertJsonStructure([
        'fullName',
        'nickName',
    ]);

test('User with in-correct credentials receives 404 status')
    ->get('/api/v1/users/nathan.dalyjunipereducation.org')
    ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
