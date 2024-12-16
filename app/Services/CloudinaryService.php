<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Exception\ApiError;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Uploads an image to Cloudinary.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     *
     * @throws ApiError
     */
    public function uploadImage($file, $folder = 'films/thumbnails'): string
    {
        $uploadResult = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => 'image',
        ]);

        return $uploadResult['secure_url'];
    }
}
