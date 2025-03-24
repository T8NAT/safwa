<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_settings = array(
            array('id' => '1','version' => '1.2.0','splash_screen_image' => '07749fa1-a0dd-48f5-9472-168a2ef056b2.webp','url_title' => 'Download the Rentify App Today','android_url' => 'https://play.google.com/','iso_url' => 'https://www.apple.com/app-store/','created_at' => '2023-11-02 16:18:39','updated_at' => '2023-12-19 09:40:07')
          );

        AppSettings::upsert($app_settings,['id'],['android_url','iso_url']);
    }
}
