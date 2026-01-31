<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaRepository
{

    public function storeByRequest(UploadedFile $file, string $path, ?string $type = null): Media
    {
        // Upload file to the public disk and get the file path
        $filePath = Storage::disk('public')->put('/' . trim($path, '/'), $file);
        // Extra file details
        $orginalName = $file->getClientOriginalName();
        $fileName = pathinfo($orginalName, PATHINFO_FILENAME);
        $uniqueFileName = $fileName . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        // Determine the file extension
        $extension = $file->getClientOriginalExtension();

        if (! $type) {
            $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']) ? 'image' : $extension;
        }
        return Media::create([
            'name' => $uniqueFileName,
            'file_name' => basename($filePath),
            'type' => $type,
            'src' => $filePath,
            'size' => $file->getSize(),
            'alt_text' => $fileName,
            'user_id' => Auth::id(),
            'extension' => $extension,
            'description' => null,
        ]);
    }

    // public function updateByRequest(array $data, Media $media): Media
    // {
    //     $basename = $data['name'];
    //     $newName = $basename;
    //     $count = 1;
    //     // Ensure the new name is unique
    //     while (Media::where('name', $newName)->where('id', '!=', $media->id)->exists()) {
    //         $newName = $basename . ' ' . $count;
    //         $count++;
    //     }
    //     $data['name'] = $newName;
    //     $media->update([
    //         'name' => $data['name'],
    //         'alt_text' => $data['alt_text'],
    //         'description' => $data['description'] ?? null,
    //     ]);
    //     return $media;
    // }

    public function updateByRequest(array $data, Media $media): Media
    {
        // Step 1: Get the base name from input
        $basename = $data['name']; // Example: "Suman Profile"
        $newName = $basename;
        $count = 1;

        // Step 2: Ensure the display name (name) is unique in the database
        // This will make it "Suman Profile 1", "Suman Profile 2", etc.
        while (Media::where('name', $newName)->where('id', '!=', $media->id)->exists()) {
            $newName = $basename . ' ' . $count;
            $count++;
        }

        // Step 3: Prepare for physical file renaming
        $oldPath = $media->src;
        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $directory = dirname($oldPath);

        // Create a slugged version of the unique display name
        // Example: "Suman Profile 1" becomes "suman-profile-1.png"
        $sluggedName = Str::slug($newName);
        $newFileNameOnly = $sluggedName . '.' . $extension;

        // Construct the full new path
        $newPath = ($directory === '.' || $directory === '/') ? $newFileNameOnly : $directory . '/' . $newFileNameOnly;

        // Step 4: Physically rename the file in storage
        if (Storage::disk('public')->exists($oldPath)) {
            // Only move if the path has actually changed
            if ($oldPath !== $newPath) {
                Storage::disk('public')->move($oldPath, $newPath);
            }
        }

        // Step 5: Update the database record with everything
        $media->update([
            'name' => $newName, // Saves: Suman Profile 1
            'file_name' => $newFileNameOnly, // Saves: suman-profile-1.png
            'src' => $newPath, // Saves: path/to/suman-profile-1.png
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
