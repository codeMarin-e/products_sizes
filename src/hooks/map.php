<?php
return [
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'config','marinar_products.php']) => [
        "// @HOOK_CONFIGS_ADDONS" => "\t\t\\Marinar\\ProductsSizes\\MarinarProductsSizes::class, \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Models', 'Product.php']) => [
        "// @HOOK_TRAITS" => "\tuse \\App\\Traits\\ProductSizesTrait; \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'resources', 'views', 'admin', 'products', 'product.blade.php']) => [
        "{{-- @HOOK_AFTER_PUSHES --}}" => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_AFTER_PUSHES.blade.php']),
        "{{-- @HOOK_AFTER_DESCRIPTION --}}" => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_AFTER_DESCRIPTION.blade.php']),
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Http', 'Controllers', 'Admin', 'ProductController.php']) => [
        '// @HOOK_EDIT' => "\$viewData['chProduct']->loadMissing('sizes');",
        '// @HOOK_STORE_END' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_STORE_END.php']),
        '// @HOOK_UPDATE_END' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_STORE_END.php']),
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Http', 'Requests', 'Admin', 'ProductRequest.php']) => [
        '// @HOOK_REQUEST_PREPARE' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_REQUEST_PREPARE.php']),
        '// @HOOK_REQUEST_RULES' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_REQUEST_RULES.php']),
        '// @HOOK_REQUEST_MESSAGES' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_REQUEST_MESSAGES.php']),
        '// @HOOK_REQUEST_AFTER_VALIDATED' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_REQUEST_AFTER_VALIDATED.php']),
    ],

];
