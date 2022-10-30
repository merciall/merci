# merci
composer require merciall/merci

Inside `composer.json`
```php
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "Merciall\\Merci\\": "vendor/merciall/merci/src/"
        }
    },
```

Inside `config/app.php`
```php
    'aliases' => [
    
        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        ...
        'Merci' => Merciall\Merci\Facades\Merci::class,
    ],
```
