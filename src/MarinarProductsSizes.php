<?php
    namespace Marinar\ProductsSizes;
    use Illuminate\Support\Facades\Artisan;

    use Marinar\ProductsSizes\Database\Seeders\MarinarProductsSizesInstallSeeder;

    class MarinarProductsSizes {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarProductsSizesInstallSeeder::class;
        }

        public static function triggeredInstalled() {
           Artisan::call('db:seed --class="\\\Marinar\\\ProductsSizes\\\Database\\\Seeders\\\MarinarProductsSizesInstallSeeder"');
           echo Artisan::output();
        }
    }
