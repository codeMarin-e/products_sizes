<?php
$validatedData = $validatedData?? [];
foreach($validatedData['sizes'] as $sizeIndex => $sizeData) {
    unset($validatedData['sizes'][$sizeIndex]['quantity']);
}
