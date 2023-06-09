<?php

declare(strict_types=1);

namespace WayOfDev\Package\Bridge\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

final class PackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/package.php' => config_path('package.php'),
            ], 'config');

            $this->registerConsoleCommands();
        }
    }

    private function registerConsoleCommands(): void
    {
        $this->commands([]);
    }
}
