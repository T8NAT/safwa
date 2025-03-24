<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Cars\CarType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $car_types = array(
            array('id' => '13','slug' => 'toyota','name' => 'Toyota','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:35:51','updated_at' => '2023-11-21 18:35:51'),
            array('id' => '14','slug' => 'hatchback','name' => 'Hatchback','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:36:06','updated_at' => '2023-11-21 18:36:06'),
            array('id' => '15','slug' => 'minivan','name' => 'Minivan','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:36:22','updated_at' => '2023-11-21 18:36:22'),
            array('id' => '16','slug' => 'sedan','name' => 'Sedan','status' => '1','last_edit_by' => '1','created_at' => '2023-11-21 18:36:36','updated_at' => '2023-11-21 18:36:36')
          );
          CarType::insert($car_types);
    }
}
