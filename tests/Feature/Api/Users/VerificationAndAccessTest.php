<?php

namespace Tests\Feature\Api\Users;

test('User with matching credentials returns valid credentials response')
    ->postJson('/api/v1/users/verify', [
        'username' => 'nathan.daly@junipereducation.org',
        'password' => 'Krypt0n1t3!',
    ])
    ->assertOk()
    ->assertJson([
        'credentialsValid' => true,
    ]);

test('User with mismatched credentials returns invalid credentials response')
    ->postJson('/api/v1/users/verify', [
        'username' => 'nathan.daly@junipereducation.org',
        'password' => 'asdsad!',
    ])
    ->assertOk()
    ->assertJson([
        'credentialsValid' => false,
    ]);

test('User has access to school with organisation ID 1')
    ->get('/api/v1/users/nathan.daly@junipereducation.org/accessControl/1')
    ->assertOk()
    ->assertJson([
        'hasAccess' => true,
        'isEnabled' => true,
        'productSpecificClaimsNamespace' => 'foo',
        'productSpecificClaims' => [],
    ]);

test('User does NOT have access to school with organisation ID 2')
    ->get('/api/v1/users/nathan.daly@junipereducation.org/accessControl/2')
    ->assertOk()
    ->assertJson([
        'hasAccess' => false,
        'isEnabled' => true,
        'productSpecificClaimsNamespace' => 'foo',
        'productSpecificClaims' => [],
    ]);
