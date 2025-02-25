<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_types';

    protected $fillable = [
        'type_name',
        'description',
        'parent_type_id',
        'is_archived',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'product_type_id');
    }

    // Self-referencing relationship
    public function parentType()
    {
        return $this->belongsTo(ProductType::class, 'parent_type_id');
    }
}
