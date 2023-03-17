<?php
    namespace Marinar\ProductsSizes\Database\Seeders;

    use App\Models\ProductSize;
    use Illuminate\Database\Seeder;
    use Marinar\ProductsSizes\MarinarProductsSizes;

    class MarinarProductsSizesRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_products_sizes';
            static::$packageDir = MarinarProductsSizes::getPackageMainDir();
        }

        public function run() {
//            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;
//
//            $this->autoRemove();
//
//            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                foreach(ProductSize::get() as $size) {
                    $size->delete();
                }
                return true;
            });
        }
    }
