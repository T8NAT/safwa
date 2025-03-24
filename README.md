<<<<<<<< Update Guide >>>>>>>>>>>
Clone Version: 1.2.0
Immediate Older Version: 1.2.0
Current Version: 1.3.0

Update Features::
1. Optimization & Bug Fixing
2. Language Added (French)
3. System Maintenance Mode Added
4. Installer Update

Please Use Those Command On Your Terminal To Update v1.2.0 to v1.3.0
1. Update Composer To Add New Package (Make Sure Your Targeted Location Is Project Root)
    composer update

2. To Added New Migration File
    php artisan migrate

3. To Update Language Please Run This Command On Your Terminal (Make Sure Your Targeted Location Is Project Root)
    php artisan db:seed --class=Database\\Seeders\\Update\\LanguageSeeder 

4. To Update Version Related Feature Please Run This Command On Your Terminal (Make Sure Your Targeted Location Is Project Root)
    php artisan db:seed --class=Database\\Seeders\\UpdateFeatureSeeder

5. To Update System Maintenance Mode Please Run This Command On Your Terminal (Make Sure Your Targeted Location Is Project Root)
    php artisan db:seed --class=Database\\Seeders\\Admin\\SystemMaintenanceSeeder

6. To clear view file cache (Make Sure Your Targeted Location Is Project Root)
    php artisan view:clear
---------------------------------------------------------------------------------

Fresh Installation Guide
1. Update Composer To Update All PHP/Laravel Packages
    composer update

2. Seed Database With Necessary Data
    php artisan migrate:fresh --seed

3. Create Token For API Authentication By Run The Command Below
    php artisan passport:install
