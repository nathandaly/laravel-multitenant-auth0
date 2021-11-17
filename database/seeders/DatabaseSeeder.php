<?php

namespace Database\Seeders;

use App\Models\DirectoryUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         DirectoryUser::factory(1)->withShardATestUser()->create();
    }
}
