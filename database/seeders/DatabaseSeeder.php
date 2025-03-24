<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\Admin\CarSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\CarAreaSeeder;
use Database\Seeders\Admin\CarTypeSeeder;
use Database\Seeders\Admin\CurrencySeeder;
use Database\Seeders\Admin\LanguageSeeder;
use Database\Seeders\Admin\SetupSeoSeeder;
use Database\Seeders\Admin\ExtensionSeeder;
use Database\Seeders\Admin\SetupPageSeeder;
use Database\Seeders\Admin\AppOnBoardSeeder;
use Database\Seeders\Admin\AppSettingsSeeder;
use Database\Seeders\Admin\AreaHasTypeSeeder;
use Database\Seeders\Admin\AdminHasRoleSeeder;
use Database\Seeders\Admin\AnnouncementSeeder;
use Database\Seeders\Admin\SiteSectionsSeeder;
use Database\Seeders\Admin\BasicSettingsSeeder;
use Database\Seeders\Admin\AnnouncementCategorySeeder;
use Database\Seeders\Admin\DemoAdminSeeder;
use Database\Seeders\Admin\SystemMaintenanceSeeder;
use Database\Seeders\Fresh\BasicSettingsSeeder as FreshBasicSettingsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //demo seeder

        // $this->call([
        //     DemoAdminSeeder::class,
        //     RoleSeeder::class,
        //     BasicSettingsSeeder::class,
        //     CurrencySeeder::class,
        //     SetupSeoSeeder::class,
        //     AppSettingsSeeder::class,
        //     AppOnBoardSeeder::class,
        //     SiteSectionsSeeder::class,
        //     ExtensionSeeder::class,
        //     AdminHasRoleSeeder::class,
        //     UserSeeder::class,
        //     SetupPageSeeder::class,
        //     LanguageSeeder::class,
        //     AnnouncementCategorySeeder::class,
        //     AnnouncementSeeder::class,
        //     CarTypeSeeder::class,
        //     CarAreaSeeder::class,
        //     AreaHasTypeSeeder::class,
        //     CarSeeder::class,
        //     SystemMaintenanceSeeder::class
        // ]);


        // fresh seeder
        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            FreshBasicSettingsSeeder::class,
            CurrencySeeder::class,
            SetupSeoSeeder::class,
            AppSettingsSeeder::class,
            AppOnBoardSeeder::class,
            SiteSectionsSeeder::class,
            ExtensionSeeder::class,
            AdminHasRoleSeeder::class,
            SetupPageSeeder::class,
            LanguageSeeder::class,
            AnnouncementCategorySeeder::class,
            AnnouncementSeeder::class,
            SystemMaintenanceSeeder::class,
            CarTypeSeeder::class,
            CarAreaSeeder::class,
            CarSeeder::class,
        ]);
    }
}
