<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("email", "admin@gmail.com")->first();
        if (!isset($user) || empty($user)) {
            $create = User::create([
                "first_name" => "John",
                "last_name" => "Doe",
                "email" => "admin@gmail.com",
                "password" => Hash::make("admin123"),
            ]);
            echo "User Created";
        } else {
            echo "User already exist";
        }
    }
}
