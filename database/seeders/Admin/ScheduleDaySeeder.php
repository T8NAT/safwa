<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Schedules\ScheduleDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedule_days = array(
            array('id' => '1','admin_id' => '1','name' => 'Saturday','slug' => 'saturday','status' => '1','created_at' => '2023-10-19 05:53:36','updated_at' => '2023-10-19 11:01:51'),
            array('id' => '2','admin_id' => '1','name' => 'Sunday','slug' => 'sunday','status' => '1','created_at' => '2023-10-19 05:53:44','updated_at' => '2023-10-19 11:01:54'),
            array('id' => '3','admin_id' => '1','name' => 'Monday','slug' => 'monday','status' => '1','created_at' => '2023-10-19 05:53:52','updated_at' => '2023-10-19 11:01:53'),
            array('id' => '4','admin_id' => '1','name' => 'Tuesday','slug' => 'tuesday','status' => '1','created_at' => '2023-10-19 05:54:02','updated_at' => '2023-10-19 05:54:02'),
            array('id' => '5','admin_id' => '1','name' => 'Wednesday','slug' => 'wednesday','status' => '1','created_at' => '2023-10-19 05:54:02','updated_at' => '2023-10-19 05:54:02'),
            array('id' => '6','admin_id' => '1','name' => 'Thursday','slug' => 'thursday','status' => '1','created_at' => '2023-10-19 05:54:09','updated_at' => '2023-10-19 05:54:09'),
            array('id' => '7','admin_id' => '1','name' => 'Friday','slug' => 'friday','status' => '1','created_at' => '2023-10-19 05:54:16','updated_at' => '2023-10-19 05:54:16')
          );

          ScheduleDay::insert($schedule_days);
    }
}
