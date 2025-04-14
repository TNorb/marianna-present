<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'item_id', 'name', 'quantity', 'price', 'discount', 'size',];

    // Itemek tartozik a rendeléshez
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // itemsből tartozik hozzá egy item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
