<?php
    namespace App\Traits;

    use App\Models\ProductSize;

    trait ProductSizesTrait {

        public static function bootProductSizesTrait() {
            static::deleting( static::class.'@onDeleting_sizes' );
            if(method_exists(Product::class, 'bootSoftDeletes') || static::hasMacro('bootSoftDeletes')) {
                static::registerModelEvent('forceDeleted', static::class.'@onForceDeleted_sizes' );
            }
        }

        public function sizes() {
            return $this->hasMany( ProductSize::class, 'product_id', 'id')->orderBy('ord', 'ASC');
        }

        public function onDeleting_sizes($model) {
            if(!method_exists(static::class, 'bootSoftDeletes') && !static::hasMacro('bootSoftDeletes')) {
                return $model->onForceDeleted_sizes($model);
            }
            foreach($model->sizes()->get() as $size) {
                $size->delete();
            }
        }

        public function onForceDeleted_sizes($model) {
            foreach($model->sizes()->withTrashed()->get() as $size) {
                $size->forceDelete();
            }
        }

        public function getDummyAttribute() {
            return $this->getDummy();
        }

        public function getDummy() {
            $this->loadMissing('sizes');
            if($this->sizes->count() == 1) {
                if($this->sizes->first()->name == '#') {
                    return $this->sizes->first();
                }
            }
        }

        public function scopeInStore($query, $sizeBld = null) {
            if($sizeBld) {
                $table = ProductSize::getModel()->getTable();
                $sizeQuantities = $sizeBld->inStore()->get()->pluck('product_id');
                return $query->whereIn("{$table}.id", $sizeQuantities);
            }
            return $query->whereHas("sizes", function($qry) {
                $qry->inStore();
            });

        }

        public function getMainSize($qryBld = null) {
            if($qryBld) {
                return $qryBld->first();
            }
            $this->loadMissing("sizes");
            return $this->sizes()->first();
            $qryBld = $qryBld?? $this->sizes();
            return $qryBld->first();
        }

    }
