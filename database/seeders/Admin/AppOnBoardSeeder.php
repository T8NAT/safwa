<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Admin\AppOnboardScreens;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppOnBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_onboard_screens = array(
            array('id' => '1', 'title' => 'Rent A Vehicle', 'sub_title' => 'At Rent A Vehicle, we understand that every journey is unique, and we\'re here to make your travel experience as seamless as possible.', 'image' => '2fb697da-4b0e-46d8-b917-ac7b03d88345.webp', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-12-02 15:13:25', 'updated_at' => '2023-12-02 15:13:25')
        );
        AppOnboardScreens::insert($app_onboard_screens);
    }
}
