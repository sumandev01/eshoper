<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key_name', 'key_value'];

    public static function get($key)
    {
        $setting = self::whereSettingKey($key)->first();
        return $setting ? $setting->key_value : null;
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }


    public function image(): Attribute
    {
        $url = asset('default.webp');
        if ($this->media && $this->media->src) {
            $url = Storage::url($this->media->src);
        }
        return Attribute::make(
            get: fn() => $url,
        );
    }
}
