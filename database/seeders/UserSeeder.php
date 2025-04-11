<?php

namespace Database\Seeders;

use Domains\Wallet\Entities\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuário Comum',
            'email' => 'user@example.com',
            'type' => 'common',
            'balance' => 1000.00,
        ]);

        User::factory()->create([
            'name' => 'Lojista',
            'email' => 'merchant@example.com',
            'type' => 'merchant',
            'balance' => 0,
        ]);

        User::factory(8)->create();
    }
}
