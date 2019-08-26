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
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__. self::DS . 'config' . self::DS . 'imageable.php' => config_path('imageable.php'),
        ], 'config');
    }
}