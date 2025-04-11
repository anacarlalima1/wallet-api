<?php

namespace Database\Factories;

use App\Helpers\DocumentHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Domains\Wallet\Entities\User;
use Domains\Wallet\Enums\UserType;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @return array
     */
    public function definition()
    {

        $type = $this->faker->randomElement(UserType::values());

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf_cnpj' => DocumentHelper::generate($type),
            'type' => $type,
            'password' => Hash::make('12345678'),
            'balance' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
