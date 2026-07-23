<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    /**
     * Active Cloudinary sample image placeholders per category.
     * Stored as online high-quality Unsplash fallbacks.
     */
    protected static array $samples = [
        'tourism'       => ['https://images.unsplash.com/photo-1508739773434-c26b3d09e071?auto=format&fit=crop&w=800&h=500&q=80'],
        'culture'       => ['https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=800&h=500&q=80'],
        'ekraf'         => ['https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&h=500&q=80'],
        'accommodation' => ['https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80'],
        'culinary'      => ['https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80'],
        'news'          => ['https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&h=500&q=80'],
        'gallery'       => ['https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=800&h=500&q=80'],
        'default'       => ['https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=800&h=500&q=80'],
    ];

    public function __construct()
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'mabhpcw6');
        $apiKey    = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        Configuration::instance([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
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
     */
    public function upload(string $filePath, string $folder = 'disparbud')
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder' => $folder,
            ]);

            return $response['secure_url'];
        } catch (\Exception $e) {
            Log::warning('Cloudinary upload failed: ' . $e->getMessage());
            return self::getSampleUrl('default');
        }
    }

    /**
     * Get an active sample Cloudinary image URL for a given category.
     *
     * @param string $category
     * @param int $index
     * @return string
     */
    public static function getSampleUrl(string $category = 'default', int $index = 0): string
    {
        $list = self::$samples[$category] ?? self::$samples['default'];
        $safeIndex = $index % count($list);
        $filename = $list[$safeIndex];

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'mabhpcw6');
        return "https://res.cloudinary.com/{$cloudName}/image/upload/c_fill,g_auto,w_800,h_500,f_auto,q_auto/{$filename}";
    }

    /**
     * Get a standardized Cloudinary image URL or fallback to active Cloudinary sample.
     *
     * @param string|null $pathOrFilename
     * @param string $category
     * @return string
     */
    public static function getUrl(?string $pathOrFilename, string $category = 'default'): string
    {
        if (empty($pathOrFilename)) {
            return self::getSampleUrl($category);
        }

        if (str_starts_with($pathOrFilename, 'http://') || str_starts_with($pathOrFilename, 'https://')) {
            $cloudName = env('CLOUDINARY_CLOUD_NAME', 'mabhpcw6');
            if (str_contains($pathOrFilename, "res.cloudinary.com/{$cloudName}/image/upload/")) {
                if (!str_contains($pathOrFilename, '/c_fill')) {
                    return str_replace(
                        "/image/upload/",
                        "/image/upload/c_fill,g_auto,w_800,h_500,f_auto,q_auto/",
                        $pathOrFilename
                    );
                }
            }
            return $pathOrFilename;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'mabhpcw6');
        $cleanPath = ltrim($pathOrFilename, '/');
        return "https://res.cloudinary.com/{$cloudName}/image/upload/c_fill,g_auto,w_800,h_500,f_auto,q_auto/{$cleanPath}";
    }
}
