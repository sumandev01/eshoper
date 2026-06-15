<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleted(function ($media) {
            if ($media->src) {
                $disk = Storage::disk('public');
                
                // Paths for all variations
                $directory = dirname($media->src);
                $filename = pathinfo($media->src, PATHINFO_FILENAME);
                
                $filesToDelete = [
                    $media->src,
                    "{$directory}/{$filename}-thumb.webp",
                    "{$directory}/{$filename}-medium.webp",
                    "{$directory}/{$filename}-large.webp",
                ];

                foreach ($filesToDelete as $file) {
                    if ($disk->exists($file)) {
                        $disk->delete($file);
                    }
                }
            }
        });
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function () {
                $directory = dirname($this->src);
                $filename = pathinfo($this->src, PATHINFO_FILENAME);
                $thumbPath = "{$directory}/{$filename}-thumb.webp";

                if (Storage::disk('public')->exists($thumbPath)) {
                    return Storage::url($thumbPath);
                }

                return $this->src && Storage::disk('public')->exists($this->src) 
                    ? Storage::url($this->src) 
                    : asset('default.webp');
            }
        );
    }

    public function mediumUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $directory = dirname($this->src);
                $filename = pathinfo($this->src, PATHINFO_FILENAME);
                $path = "{$directory}/{$filename}-medium.webp";

                return Storage::disk('public')->exists($path) 
                    ? Storage::url($path) 
                    : $this->thumbnail;
            }
        );
    }

    public function largeUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $directory = dirname($this->src);
                $filename = pathinfo($this->src, PATHINFO_FILENAME);
                $path = "{$directory}/{$filename}-large.webp";

                return Storage::disk('public')->exists($path) 
                    ? Storage::url($path) 
                    : $this->medium_url;
            }
        );
    }
}
