<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'customer_name',
        'is_paid',
        'is_discounted',
        'discount_type',
        'total_price',
        'due_date',
        'remarks',
    ];

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }
}
