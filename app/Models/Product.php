<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_type_id',
        'brand',
        'description',
        'price',
        'discounted_price_1',
        'discounted_price_2',
        'discounted_price_3',
        'purchase_wholesale_price',
        'stocks',
        'closest_expiration_date',
        'is_archived',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }
}
