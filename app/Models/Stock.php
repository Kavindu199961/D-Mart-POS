<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name', 'description', 'product_code', 'quantity', 'cost_price', 'sale_price', 'vendor_name'
    ];
}
