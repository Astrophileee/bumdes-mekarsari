<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'code',
        'id',
        'name',
        'price',
        'description',
        'photo',
    ];
        public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
