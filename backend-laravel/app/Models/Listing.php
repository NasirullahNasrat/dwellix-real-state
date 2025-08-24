<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type', 'bedrooms', 'bathrooms', 'parking', 'furnished',
        'address', 'description', 'offer', 'regularPrice',
        'discountedPrice', 'latitude', 'longitude', 'userRef', 'status'
    ];

    protected $casts = [
        'parking' => 'boolean',
        'furnished' => 'boolean',
        'offer' => 'boolean',
        'regularPrice' => 'decimal:2',
        'discountedPrice' => 'decimal:2',
    ];

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }
}