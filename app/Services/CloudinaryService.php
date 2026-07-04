<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
            ]
        ]);

        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload a file to Cloudinary.
     *
     * @param string $filePath
     * @param string $folder
     * @return string URL of the uploaded media
     * @throws \Exception
     */
    public function upload(string $filePath, string $folder = 'disparbud')
    {
        $response = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return $response['secure_url'];
    }
}
