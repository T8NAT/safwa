<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\BasicSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic_settings = array(
            array('id' => '1','site_name' => 'Rentify','site_title' => 'Car Rental Platform','base_color' => '#00C2FF','secondary_color' => '#1d252d','otp_exp_seconds' => '3200','country_code' =>'bd','web_version' => '1.3.0','location' => NULL,'timezone' => 'Asia/Dhaka','force_ssl' => '1','user_registration' => '1','secure_password' => '1','agree_policy' => '1','email_verification' => '1','email_notification' => '1','push_notification' => '1','site_logo_dark' => '9815a906-f289-48ac-9b27-2bb08bedbf6a.webp','site_logo' => '758a08c5-5ae4-4f7d-b844-0d479a2822d9.webp','site_fav_dark' => '066ee87a-91cb-4147-a12e-e58e301ac544.webp','site_fav' => '41e36a04-6433-4abb-a64b-0dff8da9b24f.webp','mail_config' => '{"method":"smtp","host":"appdevs.net","port":"465","encryption":"ssl","username":"system@appdevs.net","password":"QP2fsLk?80Ac","from":"system@appdevs.net","app_name":"Rentify"}','mail_activity' => NULL,'push_notification_config' => '{"method":"pusher","instance_id":"","primary_key":""}','push_notification_activity' => NULL,'broadcast_config' => '{"method":"pusher","app_id":"","primary_key":"","secret_key":"","cluster":"ap2"}','broadcast_activity' => NULL,'sms_config' => NULL,'sms_activity' => NULL,'created_at' => '2023-10-26 13:32:53','updated_at' => '2023-11-13 10:51:48')
        );
        BasicSettings::insert($basic_settings);
    }
}
