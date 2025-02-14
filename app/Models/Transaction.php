<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasOne(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
