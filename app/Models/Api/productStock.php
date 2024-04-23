<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'productId',
        'wholesellPrice',
        'retailPrice',
        'specialPrice',
        'lotUnit',
        'lotPrice',
        'unitName',
        'lotNumber',
        'meta',
        'status',
        'flag'
    ];
}
