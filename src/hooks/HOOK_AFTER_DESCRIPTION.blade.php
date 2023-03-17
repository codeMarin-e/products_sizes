<div class="row mb-1">
    <button class="btn btn-sm btn-success"
            id="js_add_size"
            type="button"><i class="fa fa-plus"></i> @lang('admin/products/sizes.add_size')
    </button>
</div>

<div class="row">
    <div class="table-responsive rounded ">
        <table class="table table-sm " id="js_sizes_table">
            <thead class="thead-light">
            <tr>
                <th class="text-center align-middle">@lang('admin/products/sizes.id')</th>
                {{-- @HOOK_SIZES_AFTER_ID_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.name')</th>
                {{-- @HOOK_SIZES_AFTER_NAME_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.show_name')</th>
                {{-- @HOOK_SIZES_AFTER_SHOW_NAME_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.pictures')</th>
                {{-- @HOOK_SIZES_AFTER_PICTURES_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.price') [{{$siteCurrency}}]</th>
                {{-- @HOOK_SIZES_AFTER_PRICE_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.new_price') [{{$siteCurrency}}]</th>
                {{-- @HOOK_SIZES_AFTER_NEW_PRICE_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.quantity')</th>
                {{-- @HOOK_SIZES_AFTER_QUANTITY_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.change_quantity')</th>
                {{-- @HOOK_SIZES_AFTER_CHANGE_QUANTITY_TH --}}
                <th class="text-center align-middle">@lang('admin/products/sizes.active')</th>
                {{-- @HOOK_SIZES_AFTER_ACTIVE_TH --}}
                <th class="text-center align-middle" colspan="2">@lang('admin/products/sizes.functions')</th>
            </tr>
            </thead>
            @foreach($productSizes as $sizeIndex => $sizeData)
                {{-- @HOOK_SIZES_BEFORE_SIZEDATA --}}
                @php
                    $fromOldButSize = false;
                    if(is_object($sizeData)) { //not from form submit
                        $newIndex = 0;
                        $changeQuantity = 0;
                        $showName = $sizeData->aVar("name");
                        $sizePicturesCount = $sizeData->attachments()->where('type', 'pictures')->count();
                        $sizeAttachable = $sizeData;
                    } else {
                        $newIndex = \Illuminate\Support\Str::startsWith($sizeIndex, 'new_') ? (int)\Illuminate\Support\Str::after($sizeIndex, 'new_') : 0;
                        $sizeData = json_decode(json_encode($sizeData));
                        $lastNewIndex = $lastNewIndex < $newIndex? $newIndex : $lastNewIndex;
                        $changeQuantity = $sizeData->change_quantity;
                        $showName = $sizeData->add->name;
                        $sizeAttachable = null;
                        $sizePictures = collect();
                        $sizePicturesCount = 0;
                        if(!$newIndex) {
                            $size =  $chProduct->sizes->find($sizeIndex);
                            $sizeAttachable = $size;
                            $sizePicturesCount = $size->attachments()->where('type', 'pictures')->orderBy('ord', 'ASC')->count();
                            $fromOldButSize = true;
                        }
                    }
                @endphp
                {{-- @HOOK_SIZES_AFTER_SIZEDATA --}}
                <tbody class="js_size">
                <tr>
                    <td class="text-center align-middle">{{($newIndex? '-' : $sizeIndex)}}</td>
                    {{-- @HOOK_SIZES_AFTER_ID --}}
                    <td class="">
                        <input type="text"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][name]"
                               value="{{$sizeData->name}}"
                               class="form-control @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.name")) is-invalid @endif"/>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_NAME --}}
                    <td class="">
                        <input type="text"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][add][name]"
                               value="{{$showName}}"
                               class="form-control @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.add.name")) is-invalid @endif"/>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_SHOW_NAME --}}
                    <td class="text-center w-10">
                        <button class="btn @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.pictures.*")) btn-warning @else btn-primary @endif js_pictures_btn"
                        >@lang('admin/products/sizes.pictures')@if(!$newIndex) <span class="badge badge-success ml-1">{{$sizePicturesCount}}</span>@endif
                        </button>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_PICTURES --}}
                    <td>
                        <input type="text"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][price]"
                               value="{{$sizeData->price}}"
                               class="form-control @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.price")) is-invalid @endif"/>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_PRICE --}}
                    <td>
                        <input type="text"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][new_price]"
                               value="{{$sizeData->new_price}}"
                               class="form-control @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.new_price")) is-invalid @endif"/>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_NEW_PRICE --}}
                    <td class="text-center align-middle">
                        <input type="text"
                               readonly="readonly"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][quantity]"
                               value="{{$sizeData->quantity}}"
                               class="form-control text-center"/>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_QUANTITY --}}
                    {{--                    <td class="text-center align-middle">--}}
                    {{--                        <input type="text"--}}
                    {{--                               readonly="readonly""--}}
                    {{--                               value="{{$totalQuantity}}"--}}
                    {{--                               class="form-control text-center" />--}}
                    {{--                    </td>--}}
                    <td>
                        <input type="text"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][change_quantity]"
                               value="{{$changeQuantity}}"
                               class="form-control @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.change_quantity")) is-invalid @endif"
                        />
                    </td>
                    {{-- @HOOK_SIZES_AFTER_CHANGE_QUANTITY --}}
                    <td class="text-center align-middle">
                        <input type="checkbox"
                               value="1"
                               id="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][active]"
                               name="{{$inputBag}}[{{$inputBagSizes}}][{{$sizeIndex}}][active]"
                               class="form-check-inline"
                               @if(isset($sizeData->active) && $sizeData->active) checked="checked" @endif
                        />
                    </td>
                    {{-- @HOOK_SIZES_AFTER_ACTIVE --}}
                    <td class="text-center align-middle"><i title="@lang('admin/products/sizes.drag')" class="fa fa-arrows-alt  text-primary"></i></td>
                    {{-- @HOOK_SIZES_AFTER_MOVE --}}
                    <td class="text-center">
                        <a class="btn btn-link text-danger js_size_remove"
                           title="@lang('admin/products/sizes.remove')"
                           data-remove_ask="@lang('admin/products/sizes.remove_ask')"
                        ><i class="fa fa-trash"></i></a>
                    </td>
                    {{-- @HOOK_SIZES_AFTER_REMOVE --}}
                </tr>
                <tr class="js_size_pictures @if($errors->{$inputBag}->has("{$inputBagSizes}.{$sizeIndex}.pictures.*")) table-danger @else d-none table-warning @endif">
                    <td colspan="11">
                        <x-admin.filepond
                            translations="admin/products/sizes.pictures"
                            :routeNamespace="$route_namespace"
                            type="pictures"
                            :inputBag="$inputBag.'['.$inputBagSizes.']['.$sizeIndex.']'"
                            :oldInputBag="$inputBag.'.'.$inputBagSizes.'.'.$sizeIndex"
                            :accept="'[\'image/*\']'"
                            maxFileSize="1MB"
                            :multiple="true"
                            :attachable="$sizeAttachable"
                            :querySelectorID="'size_pictures_'.$sizeIndex"
                        />
                    </td>
                </tr>
                {{-- @HOOK_SIZES_AFTER_PICTURES_ROW --}}
                </tbody>
            @endforeach
        </table>
    </div>
</div>
