<?php

use Illuminate\Support\Str;

if (!function_exists('extractShardsFromEnv')) {
    function extractShardsFromEnv(): array
    {
        $shardGroups = [];

        collect($_ENV)->each(function ($item, $key) use (&$shardGroups) {
            $stringable = Str::of($key);

            if ($stringable->startsWith('SHARD_')) {
                $shardGroups[(string)$stringable->between('_', '_')][(string)$stringable->afterLast('_')] = $item;
            }
        });

        return $shardGroups;
    }
}

if (!function_exists('buildConnectionsFromShards')) {
    function buildConnectionsFromShards(): array
    {
        $connections = [];

        collect(extractShardsFromEnv())->each(function ($item, $key) use (&$connections) {
            $connections[strtolower($key)] = [
                'driver' => $item['CONNECTION'],
                'url' => $item['URL'] ?? null,
                'host' => $item['HOST'] ?? '127.0.0.1',
                'port' => $item['PORT'] ?? '3306',
                'database' => $item['DATABASE'] ?? '',
                'username' => $item['USERNAME'] ?? 'forge',
                'password' => $item['PASSWORD'] ?? '',
                'unix_socket' => $item['SOCKET'] ?? '',
                'charset' => $item['CHARSET'] ?? 'utf8mb4',
                'collation' => $item['COLLATION'] ?? 'utf8mb4_unicode_ci',
                'prefix' => $item['PREFIX'] ?? '',
                'prefix_indexes' => $item['PREFIX_INDEXES'] ?? true,
                'strict' => $item['STRICT'] ?? true,
                'engine' => $item['ENGINE'] ?? null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => $item['OPTION_ATTR_SSL_CA'] ?? null,
                ]) : [],
            ];
        });

        return $connections;
    }
}

$databaseConfig = [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'director'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'director' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];

$databaseConfig['connections'] = array_merge(
    $databaseConfig['connections'],
    buildConnectionsFromShards()
);

return $databaseConfig;
