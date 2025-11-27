<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'transaction_number',
        'customer_id',
        'product_id',
        'price_product',
        'qty',
        'total_price',
        'payment_proof',
        'payment_status',
        'status',
    ];
        public function product()
    {
        return $this->belongsTo(Product::class);
    }
        public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
