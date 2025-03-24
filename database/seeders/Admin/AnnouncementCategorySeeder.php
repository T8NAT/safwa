<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AnnouncementCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $announcement_categories = array(
            array('id' => '1','admin_id' => '1','name' => 'Blog','slug' => 'blog','status' => '1','created_at' => '2023-11-12 16:18:42','updated_at' => '2023-11-12 16:18:42'),
            array('id' => '2','admin_id' => '1','name' => 'Booking Car','slug' => 'booking-car','status' => '1','created_at' => '2023-11-12 16:18:52','updated_at' => '2023-11-12 16:18:52'),
            array('id' => '3','admin_id' => '1','name' => 'Car Information','slug' => 'car-information','status' => '1','created_at' => '2023-11-12 16:19:03','updated_at' => '2023-11-12 16:19:03'),
            array('id' => '4','admin_id' => '1','name' => 'People Saying','slug' => 'people-saying','status' => '1','created_at' => '2023-11-12 16:19:13','updated_at' => '2023-11-12 16:19:13'),
            array('id' => '5','admin_id' => '1','name' => 'Appointment','slug' => 'appointment','status' => '1','created_at' => '2023-11-12 16:19:22','updated_at' => '2023-11-12 16:19:22')
          );

          AnnouncementCategory::insert($announcement_categories);
    }
}
