<?php
namespace Database\Seeders;
use Exception;
use Illuminate\Database\Seeder;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdateFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(BasicSettings::first()) {
            BasicSettings::first()->update([
                'web_version'       => "1.3.0",
            ]);
        }
        if(AppSettings::first()){
            AppSettings::first()->update(['version' => '1.3.0']);
        }

        try{
            update_project_localization_data();
        }catch(Exception $e) {
            // handle error
        }

        $this->updateEssentials();
    }


    /**
     * update essentials
     */
    public function updateEssentials()
    {
        // product id
        $product_id = "42c7bece-dba2-4b1e-a3ba-c1b3925a4d45";
        // update env
        modifyEnv([
            "AD_PRODUCT_ID"      => $product_id,
            "PRODUCT_KEY"        => env("PURCHASE_CODE"),
        ]);
    }
}
