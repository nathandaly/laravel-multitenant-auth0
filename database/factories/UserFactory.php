<?php

namespace Database\Factories;

use App\Models\DirectoryUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
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
        $hash = $username
            . 'password'
            . config('auth.password_salt');

        return [
            'userID' => 0,
            'schoolID' => 1,
            'clusterID' => 1,
            'firstname' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'userType' => 'ADMIN',
            'email' => $username,
            'username' => $username,
            'allowLogin' => 1,
            'providerID' => 0,
            'password' => md5($hash)
        ];
    }
}
