<?php

use App\Http\Controllers\Api\v1\Users;

Route::resource('users', Users::class)->parameters([
    'users' => 'email',
]);

Route::post('users/verify', [Users::class, 'verify'])
    ->name('user.verify');

Route::get('users/{email}/accessControl/{organisationId?}', [Users::class, 'accessControl'])
    ->name('user.access-control');
