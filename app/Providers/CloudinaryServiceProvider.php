<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use App\Services\CloudinaryService;

class CloudinaryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Bind Cloudinary as a singleton
        $this->app->singleton(Cloudinary::class, function ($app) {
            $config = [
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key'    => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true,
                ],
            ];

            Configuration::instance($config);

            return new Cloudinary();
        });

        // Bind CloudinaryService
        $this->app->singleton(CloudinaryService::class, function ($app) {
            return new CloudinaryService($app->make(Cloudinary::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
