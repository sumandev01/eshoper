<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewReply extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(ProductReview::class);
    }
}
