<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'firstname'         => "test",
                'lastname'          => "user1",
                'email'             => "user@appdevs.net",
                'username'          => "testuser1",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'created_at'        => now(),
            ],
            [
                'firstname'         => "test",
                'lastname'          => "user2",
                'email'             => "user2@appdevs.net",
                'username'          => "testuser2",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'created_at'        => now(),
            ],
            [
                'firstname'         => "test",
                'lastname'          => "user3",
                'email'             => "user3@appdevs.net",
                'username'          => "testuser3",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'created_at'        => now(),
            ],
            [
                'firstname'         => "test",
                'lastname'          => "user4",
                'email'             => "user4@appdevs.net",
                'username'          => "testuser4",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'created_at'        => now(),
            ],
            [
                'firstname'         => "test",
                'lastname'          => "user5",
                'email'             => "user5@appdevs.net",
                'username'          => "testuser5",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'created_at'        => now(),
            ],

        ];

        User::insert($data);
    }
}
