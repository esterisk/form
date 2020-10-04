<?php
namespace Esterisk\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views/form', 'form');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/esterisk/form'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
