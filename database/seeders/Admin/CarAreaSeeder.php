<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Cars\CarArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $car_areas = array(
            array('id' => '11','name' => 'Los Angeles','slug' => 'los-angeles','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:36:57','updated_at' => '2023-11-21 18:36:57'),
            array('id' => '12','name' => 'Chicago','slug' => 'chicago','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:37:18','updated_at' => '2023-11-21 18:37:18'),
            array('id' => '13','name' => 'New York','slug' => 'new-york','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:37:37','updated_at' => '2023-11-21 18:37:37'),
            array('id' => '14','name' => 'Rome','slug' => 'rome','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:37:53','updated_at' => '2023-11-21 18:37:53')
          );
          CarArea::insert($car_areas);
    }
}
