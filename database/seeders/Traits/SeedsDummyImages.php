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
        // Map category to our local demo-data folders
        $folderMap = [
            'logo' => 'logos',
            'favicon' => 'logos',
            'slider' => 'sliders',
            'banner' => 'banners',
            'category' => 'categories',
            'subcategory' => 'categories',
            'product' => 'products',
            'brand' => 'brands',
            'team' => 'team',
            'user' => 'team',
            'blog' => 'blogs',
            'feature' => 'features'
        ];

        $folderName = $folderMap[$category] ?? 'products';
        $directoryPath = database_path("seeders/demo-data/{$folderName}");

        // Find all images in the mapped directory
        $files = [];
        if (is_dir($directoryPath)) {
            $files = glob($directoryPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        }

        // If no images found in the specific category folder, fallback to products or a default
        if (empty($files)) {
            $fallbackPath = database_path("seeders/demo-data/products");
            if (is_dir($fallbackPath)) {
                $files = glob($fallbackPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            }
        }

        if (empty($files)) {
            return null; // No images available at all
        }

        // Pick a random image from the pool
        $randomFile = $files[array_rand($files)];
        
        // Copy to a temp path so UploadedFile can process it safely without deleting the original demo asset
        $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('dummy_', true) . '_' . basename($randomFile);
        copy($randomFile, $tmpFilePath);

        try {
            $uploadedFile = new UploadedFile(
                $tmpFilePath,
                basename($randomFile),
                mime_content_type($randomFile),
                null,
                true
            );

            $mediaRepo = app(MediaRepository::class);
            $media = $mediaRepo->storeByRequest($uploadedFile, 'media', $type);
            return $media->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
