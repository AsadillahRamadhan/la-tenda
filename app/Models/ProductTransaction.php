<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    protected $guarded = ['id'];
    protected $table = 'product_transaction';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
