@php
    $inputBagSizes = 'sizes';
    $productSizes = old("{$inputBag}.{$inputBagSizes}", (isset($chProduct)? $chProduct->sizes->keyBy('id') : []));
    $lastNewIndex = 0;
@endphp
@pushonce('below_templates')
<table id="js_size_template" class="d-none">
    <tbody class="js_size">
    <tr>
        <td class="text-center align-middle">-</td>
        {{-- @HOOK_SIZES_AFTER_ID_NEW --}}
        <td class="">
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][name]"
                   value="#"
                   class="form-control"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_NAME_NEW --}}
        <td class="">
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][add][name]"
                   value="#"
                   class="form-control"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_SHOW_NAME_NEW --}}
        <td class="text-center">
            <button class="btn btn-primary js_pictures_btn">@lang('admin/products/sizes.pictures')</button>
        </td>
        {{-- @HOOK_SIZES_AFTER_PICTURES_NEW --}}
        <td>
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][price]"
                   value=""
                   class="form-control"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_PRICE_NEW --}}
        <td>
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][new_price]"
                   value=""
                   class="form-control"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_NEW_PRICE_NEW --}}
        <td class="text-center align-middle">
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][quantity]"
                   readonly="readonly"
                   value="0"
                   class="form-control text-center"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_QUANTITY_NEW --}}
        {{--        <td class="text-center align-middle">--}}
        {{--            <input type="text"--}}
        {{--                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][total_quantity]"--}}
        {{--                   readonly="readonly"--}}
        {{--                   value="0"--}}
        {{--                   class="form-control text-center" />--}}
        {{--        </td>--}}
        <td>
            <input type="text"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][change_quantity]"
                   value=""
                   class="form-control"/>
        </td>
        {{-- @HOOK_SIZES_AFTER_CHANGE_QUANTITY_NEW --}}
        <td class="text-center align-middle">
            <input type="checkbox"
                   value="1"
                   id="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][active]"
                   name="{{$inputBag}}[{{$inputBagSizes}}][new___ID__][active]"
                   class="form-check-inline"
            />
        </td>
        {{-- @HOOK_SIZES_AFTER_ACTIVE_NEW --}}
        <td class="text-center align-middle"><i title="@lang('admin/products/sizes.drag')" class="fa fa-arrows-alt  text-primary"></i></td>
        {{-- @HOOK_SIZES_AFTER_MOVE_NEW --}}
        <td class="text-center">
            <a class="btn btn-link text-danger js_size_remove"
               title="@lang('admin/products/sizes.remove')"
               data-new="1"
               data-remove_ask="@lang('admin/products/sizes.remove_ask')"
            ><i class="fa fa-trash"></i></a>
        </td>
    </tr>
    <tr class="js_size_pictures d-none table-warning">
        <td colspan="11">
            <x-admin.filepond
                translations="admin/products/sizes.pictures"
                :routeNamespace="$route_namespace"
                type="pictures"
                :inputBag="$inputBag.'['.$inputBagSizes.'][new___ID__]'"
                :accept="'[\'image/*\']'"
                maxFileSize="1MB"
                :multiple="true"
                :attachable="null"
                :init="false"
                :querySelectorID="'size_pictures_new___ID__'"
            />
        </td>
    </tr>
    </tbody>
</table>
@endpushonce

@pushonce('above_css')
<!-- JQUERY UI -->
<link href="{{ asset('admin/vendor/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
@endpushonce

@pushonce('below_js')
<script language="javascript"
        type="text/javascript"
        src="{{ asset('admin/vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
@endpushonce

@pushonceOnReady('below_js_on_ready')
<script>
    var size_template = $('#js_size_template').html();
    var $sizesTable = $('#js_sizes_table');

    var addSize = function (index) {
        var size_template_filled = size_template.replace(/\_\_ID\_\_/g, index);
        $sizesTable.append($(size_template_filled));
        initiateFilePond( document.querySelector('#size_pictures_new_'+index) );
    };

    var lastNewIndex = {{$lastNewIndex}};
    $(document).on('click', '#js_add_size', function (e) {
        e.preventDefault();
        lastNewIndex++;
        addSize(lastNewIndex);
    });

    $(document).on('click', '.js_size_remove', function (e) {
        e.preventDefault();
        var $this = $(this);
        if (!confirm($this.attr('data-remove_ask'))) return false;
        var $parentTr = $this.parents('.js_size').first();
        $parentTr.remove();
    });

    $sizesTable.sortable({
        opacity: 0.6,
        cursor: 'move',
        containment: "parent",
        items: '.js_size',
        cancel: 'input, select, textarea, label, span',
    });

    $(document).on('click', '.js_pictures_btn', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $tBody = $this.parents('tbody');
        var $pictures = $tBody.find('.js_size_pictures').first();
        if($pictures.hasClass('d-none')) {
            $pictures.removeClass('d-none');
            $this.removeClass('btn-primary').addClass('btn-warning');
            return;
        }
        $pictures.addClass('d-none').removeClass('table-danger').addClass('table-warning');
        $this.addClass('btn-primary').removeClass('btn-warning');
    });
</script>
@endpushonceOnReady
