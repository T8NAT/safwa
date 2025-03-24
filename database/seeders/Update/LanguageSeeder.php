<?php

namespace Database\Seeders\Update;

use App\Models\Admin\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            array('name' => 'French','code' => 'fr','status' => '0','dir' => 'ltr','last_edit_by' => '1','created_at' => '2024-11-05 13:12:20','updated_at' => '2024-11-05 13:12:20')
          );


        Language::insert($languages);
    }
}
