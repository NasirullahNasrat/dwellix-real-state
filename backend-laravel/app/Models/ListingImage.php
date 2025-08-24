<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListingImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'listingimages'; // Add this line

    protected $fillable = [
        'listing_id', 'image_url', 'is_cover', 'upload_order', 'status'
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}