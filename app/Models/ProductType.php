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
        'isArchived',
    ];
    
    public function product()
    {
        return $this->hasOne(Product::class, 'product_type_id');
    }
}
