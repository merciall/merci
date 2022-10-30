# merci
composer require merciall/merci

Inside `composer.json`
```php
    "autoload": {
        "psr-4": {
            ...
            "Merciall\\Merci\\": "vendor/merciall/merci/src/"
        }
    },
```

Inside `config/app.php`
```php
    'aliases' => [
        ...
        'Merci' => Merciall\Merci\Facades\Merci::class,
    ],
```
