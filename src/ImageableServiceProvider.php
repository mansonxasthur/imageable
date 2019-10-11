<?php
/**
 * User: Manson
 * Date: 11/26/2018
 * Time: 3:58 PM
 */

namespace MX13\Imageable;


use Illuminate\Support\ServiceProvider;

class ImageableServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/imageable.php', 'imageable'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__. '/config/imageable.php' => config_path('imageable.php'),
        ], 'config');
    }
}