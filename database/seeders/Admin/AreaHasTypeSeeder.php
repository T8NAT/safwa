<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Cars\AreaHasType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaHasTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $area_has_types = array(
            array('id' => '1','car_area_id' => '11','car_type_id' => '13','created_at' => '2023-11-21 18:36:57','updated_at' => NULL),
            array('id' => '2','car_area_id' => '11','car_type_id' => '14','created_at' => '2023-11-21 18:36:57','updated_at' => NULL),
            array('id' => '3','car_area_id' => '11','car_type_id' => '15','created_at' => '2023-11-21 18:36:57','updated_at' => NULL),
            array('id' => '4','car_area_id' => '12','car_type_id' => '13','created_at' => '2023-11-21 18:37:18','updated_at' => NULL),
            array('id' => '5','car_area_id' => '12','car_type_id' => '15','created_at' => '2023-11-21 18:37:18','updated_at' => NULL),
            array('id' => '6','car_area_id' => '12','car_type_id' => '16','created_at' => '2023-11-21 18:37:18','updated_at' => NULL),
            array('id' => '7','car_area_id' => '13','car_type_id' => '13','created_at' => '2023-11-21 18:37:37','updated_at' => NULL),
            array('id' => '8','car_area_id' => '13','car_type_id' => '15','created_at' => '2023-11-21 18:37:37','updated_at' => NULL),
            array('id' => '9','car_area_id' => '13','car_type_id' => '16','created_at' => '2023-11-21 18:37:37','updated_at' => NULL),
            array('id' => '10','car_area_id' => '14','car_type_id' => '13','created_at' => '2023-11-21 18:37:54','updated_at' => NULL),
            array('id' => '11','car_area_id' => '14','car_type_id' => '14','created_at' => '2023-11-21 18:37:54','updated_at' => NULL),
            array('id' => '12','car_area_id' => '14','car_type_id' => '15','created_at' => '2023-11-21 18:37:54','updated_at' => NULL),
            array('id' => '13','car_area_id' => '14','car_type_id' => '16','created_at' => '2023-11-21 18:37:54','updated_at' => NULL)
          );
          AreaHasType::insert($area_has_types);
    }
}
