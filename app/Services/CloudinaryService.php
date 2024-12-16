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
     * @return string URL của hình ảnh
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

    /**
     * Deletes an image from Cloudinary based on its URL.
     *
     * @param string $imageUrl
     * @return bool
     */
    public function deleteImageByUrl(string $imageUrl): bool
    {
        try {
            // Parse the URL to extract the public_id
            $publicId = $this->extractPublicId($imageUrl);

            if ($publicId) {
                // Gọi API Cloudinary để xóa hình ảnh
                $this->cloudinary->uploadApi()->destroy($publicId, [
                    'resource_type' => 'image',
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            \Log::error('Cloudinary Deletion Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Trích xuất public_id từ URL hình ảnh Cloudinary.
     *
     * @param string $imageUrl
     * @return string|null
     */
    protected function extractPublicId(string $imageUrl): ?string
    {
        // Giả sử URL có định dạng: https://res.cloudinary.com/<cloud_name>/image/upload/v<version>/<public_id>.<format>
        // Bạn cần trích xuất phần <public_id>

        $parsedUrl = parse_url($imageUrl);

        if (!isset($parsedUrl['path'])) {
            return null;
        }

        $path = $parsedUrl['path'];

        // Loại bỏ phần đầu tiên '/' từ đường dẫn
        $path = ltrim($path, '/');

        // Tách các phần của đường dẫn
        $segments = explode('/', $path);

        // Tìm vị trí 'upload' trong đường dẫn
        $uploadIndex = array_search('upload', $segments);

        if ($uploadIndex === false || !isset($segments[$uploadIndex + 2])) {
            return null;
        }

        // Phần sau 'upload/v<version>/'
        $publicIdWithExtension = $segments[$uploadIndex + 2];

        // Loại bỏ phần mở rộng (.jpg, .png, ...)
        $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);

        // Nếu có thêm thư mục, cần tái tạo lại đường dẫn
        // Ví dụ: nếu public_id là 'films/thumbnails/my-film', cần tái tạo nó từ các segment

        // Lấy tất cả các segment sau 'upload/v<version>/'
        $publicIdSegments = array_slice($segments, $uploadIndex + 2);

        // Loại bỏ phần mở rộng ở phần cuối
        $lastSegment = array_pop($publicIdSegments);
        $lastSegment = pathinfo($lastSegment, PATHINFO_FILENAME);
        $publicIdSegments[] = $lastSegment;

        // Kết hợp lại các segment để tạo public_id hoàn chỉnh
        $publicId = implode('/', $publicIdSegments);

        return $publicId;
    }
}
