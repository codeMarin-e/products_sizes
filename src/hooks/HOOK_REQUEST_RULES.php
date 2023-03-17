<?php
$chProduct = $chProduct?? null;
if(isset($chProduct)) $chProduct->loadMissing('sizes');
$rules = array_merge( $rules ?? [], [
    'sizes' => 'required',
    'sizes.*.name' => ['required', 'distinct'],
    'sizes.*.add.name' => ['nullable'],
    'sizes.*.price' => [],
    'sizes.*.new_price' => [],
    'sizes.*.change_quantity' => [
        function($attribute, $value, $fail) use ($chProduct) {
            if(config('app.MINUS_QUANTITIES')) return;
            $attributeParts = explode('.', $attribute);
            $sizeIndex = $attributeParts[1];
            if(\Illuminate\Support\Str::startsWith($sizeIndex, 'new_')) {
                if($value < 0) return $fail(trans("admin/products/sizes_validation.sizes.change_quantity.below_0"));
                return;
            }

            if( !($productSize = $chProduct->sizes->find((int)$sizeIndex)) ) return;
            try {
                $productSize->change_quantity = $value ;
            } catch(\Illuminate\Validation\ValidationException $exp) {
                return $fail( $exp->getMessage() );
            }
        }],
    'sizes.*.active' => ['boolean'],
    'sizes.*.pictures' => ['nullable', 'array',  function($attribute, $value, $fail) use ($chProduct) {
        $sizeIndex = explode('.', $attribute)[1];
        $type = 'pictures';
        $inputName = "product[sizes][{$sizeIndex}][{$type}]";
        $attachIds = array();
//        $value = [ $value ]; //to can just copy/paste
        foreach($value as $index => $attachTypeId) {
            $attachIds[(int)str_replace(["{$inputName}_", "{$type}_"], '', $attachTypeId) ] = $index;
        }
        $return = \App\Models\Attachment::where([
            'attachable_id' => null,
            'attachable_type' => null,
            'session_id' => session()->getId(),
            'type' => $inputName
        ])->whereIn('id', array_keys($attachIds))->get()->keyBy('id');

        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/gif'];
        foreach($return as $attach) {
            //make some additional validation - may use new rules key pictures.*, too
            if(!in_array(
                \Illuminate\Support\Facades\Storage::disk( $attach->disk )->mimeType($attach->getFilePath()),
                $allowedMimeTypes
            )) {
                return $fail( trans('admin/products/sizes_validation.sizes.*.pictures.*.mime') );
            }
        }
        if($chSize = $chProduct->sizes->find($sizeIndex)) {
            $return = $return->union( \App\Models\Attachment::where([
                'attachable_id' => $chSize->id,
                'attachable_type' => get_class($chSize),
                'session_id' => null,
                'type' => $type
            ])->whereIn('id', array_keys($attachIds))->get()->keyBy('id') );
        }
        //sorting
        $this->mergeReturn["sizes_{$sizeIndex}_pictures"] = collect();
        foreach($attachIds as $attachId => $index) {
            if(!isset($return[$attachId])) continue;
            $this->mergeReturn["sizes_{$sizeIndex}_pictures"]->push( $return[$attachId] );
        }
    }]
]);
