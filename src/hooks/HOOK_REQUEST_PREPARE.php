<?php
$inputs[$inputBag]['sizes'] = $inputs[$inputBag]['sizes']?? [];
foreach($inputs[$inputBag]['sizes'] as $sizeIndex => $sizeData) {
    $inputs[$inputBag]['sizes'][$sizeIndex]['active'] = isset($sizeData['active']);
    $inputs[$inputBag]['sizes'][$sizeIndex]['price'] = (float)str_replace(',', '.', $inputs[$inputBag]['sizes'][$sizeIndex]['price']);
    $inputs[$inputBag]['sizes'][$sizeIndex]['new_price'] = (float)str_replace(',', '.', $inputs[$inputBag]['sizes'][$sizeIndex]['new_price']);
    $inputs[$inputBag]['sizes'][$sizeIndex]['change_quantity'] = (int)$inputs[$inputBag]['sizes'][$sizeIndex]['change_quantity'];
}
