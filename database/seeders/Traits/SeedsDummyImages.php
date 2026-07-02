<?php

namespace Database\Seeders\Traits;

use App\Repositories\MediaRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

trait SeedsDummyImages
{
    /**
     * @var array Cache of downloaded images to avoid slow network requests during seeding.
     */
    protected static array $downloadedImages = [];

    /**
     * Downloads an image from the internet and passes it through the MediaRepository.
     * Uses a caching mechanism to reuse a few downloaded images to keep seeding fast.
     *
     * @param int $width
     * @param int $height
     * @param string $type
     * @param string $category (Optional) keyword or category to differentiate cache
     * @param int $variations (Optional) How many unique images to keep in pool for this category
     * @return int|null Returns the inserted Media ID
     */
    protected function seedImage(int $width, int $height, string $type = 'image', string $category = 'general', int $variations = 3): ?int
    {
        $cacheKey = "{$width}_{$height}_{$category}";
        
        // Initialize pool for this cache key if not exists
        if (!isset(self::$downloadedImages[$cacheKey])) {
            self::$downloadedImages[$cacheKey] = [];
        }

        // If we haven't reached the variation limit, download a new unique image
        if (count(self::$downloadedImages[$cacheKey]) < $variations) {
            
            // Map category to a relevant keyword for loremflickr
            $keyword = 'fashion,clothing';
            if ($category === 'team' || $category === 'user') $keyword = 'portrait,face';
            elseif ($category === 'logo' || $category === 'brand') $keyword = 'logo,brand';
            elseif ($category === 'slider') $keyword = 'fashion,banner';
            elseif ($category === 'about') $keyword = 'office,startup';
            elseif ($category === 'category' || $category === 'subcategory') $keyword = 'clothing,apparel';
            elseif ($category === 'favicon') $keyword = 'icon';

            $url = "https://loremflickr.com/{$width}/{$height}/{$keyword}?lock=" . rand(1, 10000);
            
            try {
                $response = Http::timeout(10)->get($url);
                if ($response->successful()) {
                    $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('dummy_', true) . '.jpg';
                    file_put_contents($tmpFilePath, $response->body());
                    
                    $uploadedFile = new UploadedFile(
                        $tmpFilePath,
                        "dummy_{$category}.jpg",
                        'image/jpeg',
                        null,
                        true
                    );
                    
                    self::$downloadedImages[$cacheKey][] = $uploadedFile;
                }
            } catch (\Exception $e) {
                // If network fails, fail gracefully and return null
                return null;
            }
        }

        // If we still have no images (network failed), return null
        if (empty(self::$downloadedImages[$cacheKey])) {
            return null;
        }

        // Randomly pick one image from the downloaded pool for this category
        $uploadedFile = self::$downloadedImages[$cacheKey][array_rand(self::$downloadedImages[$cacheKey])];

        try {
            $mediaRepo = app(MediaRepository::class);
            $media = $mediaRepo->storeByRequest($uploadedFile, 'media', $type);
            return $media->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
