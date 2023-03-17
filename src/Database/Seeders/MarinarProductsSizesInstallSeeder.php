<?php
    namespace Marinar\ProductsSizes\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\ProductsSizes\MarinarProductsSizes;

    class MarinarProductsSizesInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_products_sizes';
            static::$packageDir = MarinarProductsSizes::getPackageMainDir();
        }

        public function run() {

            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }
    }
