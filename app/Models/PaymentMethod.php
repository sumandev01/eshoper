<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'media_id', 'order'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
