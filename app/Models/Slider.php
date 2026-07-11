<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function getLinkAttribute()
    {
        return \App\Services\UrlGeneratorService::resolve($this->link_type, $this->link_ref_id);
    }
}
