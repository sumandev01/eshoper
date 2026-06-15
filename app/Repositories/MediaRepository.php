<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaRepository
{

    public function storeByRequest(UploadedFile $file, string $path, ?string $type = null): Media
    {
        $manager = new ImageManager(new Driver());
        
        // Extra file details
        $orginalName = $file->getClientOriginalName();
        $fileName = pathinfo($orginalName, PATHINFO_FILENAME);
        $sluggedName = Str::slug($fileName) . '_' . strtolower(Str::random(8));
        $directory = trim($path, '/');
        
        // Define paths
        $mainPath = "{$directory}/{$sluggedName}.webp";
        $thumbPath = "{$directory}/{$sluggedName}-thumb.webp";
        $mediumPath = "{$directory}/{$sluggedName}-medium.webp";
        $largePath = "{$directory}/{$sluggedName}-large.webp";

        // Read and convert to WebP
        $image = $manager->read($file);
        
        // Save variants
        Storage::disk('public')->put($mainPath, (string) $image->toWebp(80));
        Storage::disk('public')->put($thumbPath, (string) (clone $image)->scale(width: 300)->toWebp(80));
        Storage::disk('public')->put($mediumPath, (string) (clone $image)->scale(width: 600)->toWebp(80));
        Storage::disk('public')->put($largePath, (string) (clone $image)->scale(width: 1200)->toWebp(80));

        if (! $type) {
            $type = 'image';
        }

        return Media::create([
            'name' => $sluggedName . '.webp',
            'file_name' => $sluggedName . '.webp',
            'type' => $type,
            'src' => $mainPath,
            'size' => Storage::disk('public')->size($mainPath),
            'alt_text' => $fileName,
            'user_id' => Auth::id(),
            'description' => null,
        ]);
    }

    public function updateByRequest(array $data, Media $media): Media
    {
        // Step 1: Get the base name from input
        $basename = $data['name'];
        $newName = $basename;
        $count = 1;

        while (Media::whereName($newName)->where('id', '!=', $media->id)->exists()) {
            $newName = $basename . ' ' . $count;
            $count++;
        }

        // Step 2: Prepare for physical file renaming
        $oldPath = $media->src;
        $directory = dirname($oldPath);
        $sluggedName = Str::slug($newName);
        
        $newPath = "{$directory}/{$sluggedName}.webp";
        $newThumbPath = "{$directory}/{$sluggedName}-thumb.webp";
        $newMediumPath = "{$directory}/{$sluggedName}-medium.webp";
        $newLargePath = "{$directory}/{$sluggedName}-large.webp";

        // Rename all variations
        if (Storage::disk('public')->exists($oldPath)) {
            if ($oldPath !== $newPath) {
                Storage::disk('public')->move($oldPath, $newPath);
                
                // Rename variants if they exist
                $oldBase = pathinfo($oldPath, PATHINFO_FILENAME);
                $variants = ['-thumb', '-medium', '-large'];
                foreach ($variants as $variant) {
                    $oldV = "{$directory}/{$oldBase}{$variant}.webp";
                    $newV = "{$directory}/{$sluggedName}{$variant}.webp";
                    if (Storage::disk('public')->exists($oldV)) {
                        Storage::disk('public')->move($oldV, $newV);
                    }
                }
            }
        }

        // Step 3: Update database record
        $media->update([
            'name' => $newName,
            'file_name' => "{$sluggedName}.webp",
            'src' => $newPath,
            'alt_text' => $data['alt_text'] ?? $newName,
            'description' => $data['description'] ?? $media->description,
        ]);

        return $media;
    }
    /**
     * Fetch all data.
     */
    public function all()
    {
        // Logic goes here
    }

    /**
     * Find data by ID.
     */
    public function find($id)
    {
        // Logic goes here
    }
}
