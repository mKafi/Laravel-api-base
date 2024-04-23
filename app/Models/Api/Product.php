<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode', 
        'productCode', 
        'productTitle', 
        'wholesellPrice', 
        'retailPrice', 
        'specialPrice', 
        'initialUnit', 
        'lotNumber', 
        'tags'
    ];
}
