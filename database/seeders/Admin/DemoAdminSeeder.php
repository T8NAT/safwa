<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DemoAdminSeeder extends Seeder
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
                'firstname'     => "Super",
                'lastname'      => "Admin",
                'username'      => "superadmin",
                'email'         => "superadmin@appdevs.net",
                'password'      => Hash::make("appdevs"),
                'created_at'    => now(),
                'status'        => true,
            ],
            [
                'firstname'     => "Ad",
                'lastname'      => "Min",
                'username'      => "admin",
                'email'         => "admin@appdevs.net",
                'password'      => Hash::make("appdevs"),
                'created_at'    => now(),
                'status'        => true,
            ],
            [
                'firstname'     => "Sub",
                'lastname'      => "Admin",
                'username'      => "subadmin",
                'email'         => "subadmin@appdevs.net",
                'password'      => Hash::make("appdevs"),
                'created_at'    => now(),
                'status'        => true,
            ],

        ];

        Admin::insert($data);
    }
}
