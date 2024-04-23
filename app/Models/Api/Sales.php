<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode', 
        'sellingDate',
        'name', 
        'address',
        'reference', 
        'phone', 
        'comment', 
        'salesPoint',
        'salesAgent',
        'status'
    ];
}
