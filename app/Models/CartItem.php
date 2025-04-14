<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'item_id', 'quantity', 'size'];

    // Kosárba tett itemhez tartozik egy kosár
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // kosárba tett itemhez tartozik egy item az items táblából (csak aktív)
    public function item()
    {
        return $this->belongsTo(Item::class)->where('status', 1);
    }
}
