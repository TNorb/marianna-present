<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock', 'discount', 'category', 'similar', 'sizes', 'status', 'fragile',];

    // Itemhez tartozó képek - több is lehet
    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    // Itemhez kedvezménye - ha a discount nagyobb 0-nál akkor azt az árat küldi
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->price * ((100 - $this->discount) / 100);
        }
        return $this->price;
    }
}
