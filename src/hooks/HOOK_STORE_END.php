<?php
$sizeOrd = 0;
$orderList = [];
foreach($validatedData['sizes'] as $sizeIndex => $sizeData) {
    $sizeOrd++;
    if(\Illuminate\Support\Str::startsWith($sizeIndex, 'new_')) {
        $orderList[$sizeOrd] = $chProduct->sizes()->create($sizeData);
    } else {
        $orderList[$sizeOrd] = $chProduct->sizes()->updateOrCreate([
            'id' => $sizeIndex
        ], $sizeData);
    }
    $orderList[$sizeOrd]->setAVars($sizeData['add']);
    $orderList[$sizeOrd]->reAttachAndOrder( $validatedData["sizes_{$sizeIndex}_pictures"] ?? [], 'pictures' );
}
$orderList = collect($orderList)->pluck('id')->toArray();
foreach($chProduct->sizes()->whereNotIn('id', $orderList)->get() as $size) $size->delete();
\App\Models\ProductSize::orderList($orderList);
