<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    use HasFactory;
    protected $fillable = [
        'productId',
        'description',
        'model',
        'origin',
        'company',
        'variant',
        'comment',
        'otherMeta',
        'status',
        'flag'
    ];
}
