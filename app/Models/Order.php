<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'product',
        'image',
        'description',
        'quantity',
        'tax_percentage',
        'tax_amount',
        'total_amount'
    ];

}
