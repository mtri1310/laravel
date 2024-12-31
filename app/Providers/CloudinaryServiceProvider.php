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
        $this->app->singleton(Cloudinary::class, function ($app) {
            $cloudinaryUrl = env('CLOUDINARY_URL');

            // Kiểm tra xem CLOUDINARY_URL có tồn tại
            if (!$cloudinaryUrl) {
                throw new \Exception('CLOUDINARY_URL is not set in the .env file.');
            }

            $parsedUrl = parse_url($cloudinaryUrl);

            if (!$parsedUrl) {
                throw new \Exception('Invalid CLOUDINARY_URL format.');
            }

            // Extract các thành phần từ URL
            $scheme = $parsedUrl['scheme'] ?? null;
            $user = $parsedUrl['user'] ?? null;
            $pass = $parsedUrl['pass'] ?? null;
            $host = $parsedUrl['host'] ?? null;

            if (!$scheme || !$user || !$pass || !$host) {
                throw new \Exception('Incomplete CLOUDINARY_URL.');
            }

            $cloudName = $host;      // Lấy cloud_name từ host
            $apiKey = $user;         // API Key từ user
            $apiSecret = $pass;      // API Secret từ pass

            // Thiết lập cấu hình
            $config = new Configuration([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key'    => $apiKey,
                    'api_secret' => $apiSecret,
                ],
                'url' => [
                    'secure' => true,
                ],
            ]);

            return new Cloudinary($config);
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
