<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'name', 'phone','email', 'country', 'city',
                            'zip', 'province', 'address', 'total_price', 'delivery_note',
                            'operator_id', 'place_id', 'fragile', 'unique_barcode', 'ref_code'];

    // Egy rendeléshez tartozik egy user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Rendeléshez tartozik item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Rendeléshez tartozó itemek
    public function items()
{
    return $this->hasMany(OrderItem::class);
}
}
