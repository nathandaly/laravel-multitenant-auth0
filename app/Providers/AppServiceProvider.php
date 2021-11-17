<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Pupil Asset User API",
 *      description="Pupil Asset User API",
 *      @OA\Contact(
 *          email="nathan.daly@junipereducation.org"
 *      ),
 * )
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
