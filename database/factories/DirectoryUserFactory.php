<?php

namespace Database\Factories;

use App\Models\DirectoryUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class DirectoryUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DirectoryUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $username = $this->faker->unique()->safeEmail();

        return [
            'username' => $username,
            'pwd' => $this->generatePasswordHash($username),
            'token' => null,
            'shards' => 'A',
        ];
    }

    public function withShardATestUser()
    {
        return $this->state(function (array $attributes) {
            $username = 'test.shard.a@junipereducation.org';

            return [
                'username' => $username,
                'pwd' => $this->generatePasswordHash($username),
                'shards' => 'A',
            ];
        });
    }

    private function generatePasswordHash(string $username, string $password = 'password'): string
    {
        return md5(
            $username
            . $password
            . config('auth.password_salt')
        );
    }
}
