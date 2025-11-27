<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStock extends Model
{
    protected $table = 'log_stocks';
    protected $fillable = [
        'id',
        'type',
        'initial_stock',
        'change_amount',
        'final_stock',
        'notes',
    ];
}
