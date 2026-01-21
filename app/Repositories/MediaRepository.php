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

    public function updateByRequest(array $data, Media $media): Media
    {
        $basename = $data['name'];
        $newName = $basename;
        $count = 1;
        // Ensure the new name is unique
        while (Media::where('name', $newName)->where('id', '!=', $media->id)->exists()) {
            $newName = $basename . ' ' . $count;
            $count++;
        }
        $data['name'] = $newName;
        $media->update([
            'name' => $data['name'],
            'alt_text' => $data['alt_text'],
            'description' => $data['description'] ?? null,
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
