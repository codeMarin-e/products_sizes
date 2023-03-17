<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\ProductsSizes\Database\Seeders\MarinarProductsSizesInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\ProductsSizes\Database\Seeders\MarinarProductsSizesRemoveSeeder"',
        ]
	];
