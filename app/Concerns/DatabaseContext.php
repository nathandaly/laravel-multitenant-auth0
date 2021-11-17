<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Support\Facades\Config;

trait DatabaseContext
{
    protected function switchDbContext(string $connection): void
    {
        Config::set('database.default', $connection);
    }
}
