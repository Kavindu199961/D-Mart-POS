<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirtyDayReport extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'total_sales',
        'total_profit',
        'total_cost',
    ];
}
