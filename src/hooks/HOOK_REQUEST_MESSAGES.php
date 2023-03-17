<?php
$return = array_merge(
    \Illuminate\Support\Arr::dot((array)trans('admin/products/sizes_validation')),
    $return?? []);

