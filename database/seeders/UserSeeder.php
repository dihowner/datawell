<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for($i= 1; $i<=200; $i++) {
            User::create([
                "fullname" => $faker->name(),
                'username' => $faker->name(),
                'phone_number' => $faker->phoneNumber(),
                'emailaddress' => $faker->email(),
                'password' => Hash::make('password'),
                'plan_id' => 1
            ]);            
        }
        
    }
}