<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('test123');

        User::create([
            'name' => 'admin',
            'email' => 'admin@test.ru',
            'password' => $password
        ]);

        for($i = 0; $i<3; $i++){
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $password,
            ]);
        }
        
    }
}
