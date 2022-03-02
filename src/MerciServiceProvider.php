<?php

namespace Merciall\Merci;

use Illuminate\Support\ServiceProvider;

class MerciServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->mergeConfigFrom(
            $this->getConfigFile(),
            'merci'
        );

        $services = $this->get_services();

        foreach ($services as $service) {
            $service = explode(".", $service)[0];

            $name = ucfirst($service);

            $class = "Merciall\\Merci\\App\\Services\\$name";

            Merci::macro($service, function ($var) use ($class) {
                return new $class($var);
            });
        }
    }

    public function register()
    {
        $this->app->make('Merciall\Merci\Merci');

        $this->app->bind('merci', function () {
            return new Merci;
        });
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'merci.php';
    }

    private function get_services()
    {
        $dir = __DIR__ . "/App/Services";

        $files = scandir($dir);

        return array_filter($files, fn ($file) => $file !== BASE_FILENAME && !str_starts_with($file, ".") && $file !== "..");
    }
}
