<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ImageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('image.manager', function ($app) {
            // Log::info("Image Driver: " . $app['config']['app.image_driver']);
            $driver = $app['config']['app.image_driver'] === 'gd'
                ? new GdDriver()
                : new ImagickDriver();

            return new ImageManager($driver);
        });
    }
}
