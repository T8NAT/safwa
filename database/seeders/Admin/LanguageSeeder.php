<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = array(
                    array('id' => '1','name' => 'English','code' => 'en','dir' => 'ltr','status' => '1','last_edit_by' => '1','created_at' => '2023-10-24 17:36:15','updated_at' => '2023-10-24 17:36:15'),
                    array('id' => '3','name' => 'Spanish','code' => 'es','dir' => 'ltr','status' => '0','last_edit_by' => '1','created_at' => '2023-10-25 10:51:38','updated_at' => '2023-10-25 10:51:38'),
                    array('id' => '4','name' => 'Hindi','code' => 'hi','dir' => 'ltr','status' => '0','last_edit_by' => '1','created_at' => '2023-10-25 10:52:43','updated_at' => '2023-10-25 10:52:43'),
                    array('id' => '5','name' => 'Arabic','code' => 'ar','dir' => 'rtl','status' => '0','last_edit_by' => '1','created_at' => '2023-10-25 10:53:08','updated_at' => '2023-10-25 10:53:08'),

                    array('id' => '6','name' => 'French','code' => 'fr','dir' => 'ltr','status' => '0','last_edit_by' => '1','created_at' => '2024-11-06 12:05:22','updated_at' => '2024-11-06 12:05:22')

          );

        Language::insert($languages);
    }
}
