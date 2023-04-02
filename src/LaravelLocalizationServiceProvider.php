<?php

namespace Alifrzb\LaravelLocalization;

use Illuminate\Support\ServiceProvider;
use Alifrzb\LaravelLocalization\Console\PublishLocalizationFiles;

class LaravelLocalizationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishLocalizationFiles::class
            ]);
        }
    }
}
