<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    protected $table = 'harvests';
    protected $fillable = [
        'id',
        'date',
        'quality',
        'weight_in',
        'harvest_source',
        'price_per_kg',
        'total_price',
        'notes',
    ];

}
