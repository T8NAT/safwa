<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Cars\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cars = array(
            array('id' => '9','car_area_id' => '12','car_type_id' => '13','slug' => 'b4670766-9fc4-4767-b176-6d0b273afcdf','car_model' => 'Toyota Corolla','seat' => '4','experience' => '2','car_number' => 'ABC-201','fees' => '0.50000000','image' => '889b35db-fd7e-4f3a-93eb-b776a6ddd6e3.webp','status' => '1','created_at' => '2023-11-21 18:42:58','updated_at' => '2023-11-21 18:42:58'),
            array('id' => '10','car_area_id' => '11','car_type_id' => '13','slug' => 'dfddb0f2-66fc-412e-b2f2-1b5888b00876','car_model' => 'Toyota Prius','seat' => '4','experience' => '2','car_number' => 'ABC-202','fees' => '0.40000000','image' => '2115077f-b71d-4447-b139-5d0a285d4650.webp','status' => '1','created_at' => '2023-11-21 18:43:40','updated_at' => '2023-11-21 18:43:40'),
            array('id' => '11','car_area_id' => '13','car_type_id' => '13','slug' => '869570da-16fb-400d-807f-642e75fb0c2e','car_model' => 'Toyota Camry','seat' => '4','experience' => '2','car_number' => 'ABC-203','fees' => '0.50000000','image' => 'f114eacf-696c-4e29-a736-c56b545883e2.webp','status' => '1','created_at' => '2023-11-21 18:44:18','updated_at' => '2023-11-21 18:44:18'),
            array('id' => '12','car_area_id' => '14','car_type_id' => '13','slug' => '758cd2c8-24ec-4949-85a8-63ae36b7bcc1','car_model' => 'Toyota Crown','seat' => '4','experience' => '2','car_number' => 'ABC-206','fees' => '0.50000000','image' => '75a942bc-684f-4563-ae30-38b538d4de60.webp','status' => '1','created_at' => '2023-11-21 18:44:55','updated_at' => '2023-11-21 18:44:55')
          );
          Car::insert($cars);
    }
}
