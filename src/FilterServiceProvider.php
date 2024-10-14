<?php

namespace Ivus\Filter;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ivus\Filter\Interfaces\Services\{QueryServiceInterface, RuleServiceInterface, FilterServiceInterface};
use Ivus\Filter\Services\Queries\QueryService;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filters.php', 'filters'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'filters');
        $this->publishes([
            __DIR__ . ' /../config/filters.php' => config_path('filters.php'),
        ]);
    }
}
