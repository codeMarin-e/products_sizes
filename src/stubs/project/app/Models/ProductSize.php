<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use App\Models\Product;
    use App\Traits\AddVariable;
    use App\Traits\MacroableModel;
    use App\Traits\Orderable;
    use App\Traits\Discountable;
    use App\Traits\Attachable;

    use Dotenv\Exception\ValidationException;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class ProductSize extends Model {

        protected $table = 'sizes';
        protected $fillable = ['name', 'active', 'price', 'new_price', 'quantity', 'ord', 'deleted_at', 'vat'];

        use SoftDeletes;

        use MacroableModel;
        use AddVariable;
        use Discountable;

        // @HOOK_TRAITS

        //ORDERABLE
        use Orderable;
        public function orderableQryBld($qryBld = null) {
            $qryBld = $qryBld? clone $qryBld : $this;
            return $qryBld->where([
                [ 'product_id', $this->product_id ],
            ]);
        }
        //END ORDERABLE

        //ATTACHABLE
        use Attachable;
        public static $attach_folder = 'sizes';
        //END ATTACHABLE

        public function product() {
            return $this->belongsTo( Product::class, 'product_id');
        }

        public function setChangeQuantityAttribute($quantity) {
            $nowQuantity = $this->quantity;
            $newQuantity = $nowQuantity + (float)$quantity;
            if($newQuantity < 0) {
                if(!config('app.MINUS_QUANTITIES')) {
                    throw new ValidationException(
                        trans("admin/products/sizes/validation.sizes.*.change_quantity.below_0")
                    );
                }
            }
            $this->quantity = $newQuantity;
        }

        public function getDiscountValue($price = null) {
            $price = is_numeric($price)? $price : $this->getPrice(true, false, false);
            $return = 0;
            foreach($this->activeDiscounts() as $discount) {
                //may put your logic here
                $return += $discount->getValue($price);
            }
            return $return;
        }

        public function getVatPercent() {
            if(is_null($this->vat))
                return (float)config('app.VAT');
            return (float)$this->vat;
        }

        public function getVat($price = null, $withDiscounts = true) {
            $price = is_numeric($price)? $price : $this->getPrice(true, $withDiscounts, false);
            $vatPercent = $this->getVatPercent();
            if(config('app.VAT_IN_PRICE')) {
                return $price - ( $price / ( 1 + ($vatPercent/100) ) );
            }
            return $price * ($vatPercent/100);
        }

        public function getPrice($withNew = true, $withDiscounts = true, $withVat = true) {
            $price = $this->price;
            if($withNew && ($newPrice = $this->new_price)) {
                $price = $newPrice;
            }
            if($withDiscounts) {
                $price -= $this->getDiscountValue($price);
            }
            if(config('app.VAT_IN_PRICE')) {
                if(!$withVat) {
                    $price -= $this->getVat($price, $withDiscounts);
                }
            } else {
                if($withVat) {
                    $price += $this->getVat($price, $withDiscounts);
                }
            }
            return $price;
        }

        public function scopeInStore($query) {
            if(config('app.MINUS_QUANTITIES')) {
                return $query;
            }
            return $query->where('quantity', '>', 0);
        }

        public function getCartProductName() {
            $sizeName = $this->aVar('name');
            if($sizeName == '#') return $this->product->aVar('name');
            return $this->product->aVar('name').'-'.$sizeName;
        }

        public function getReference($cartProduct) {
            return 'size_'.$this->id;
        }
    }
