<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $guarded = ['id'];

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Initialize a default value to avoid undefined variable error
                $url = null; 

                // Check if src exists and file is present in storage
                if ($this->src && Storage::disk('public')->exists($this->src)) {
                    $url = Storage::url($this->src);
                }

                return $url;
            }
        );
    }
}
