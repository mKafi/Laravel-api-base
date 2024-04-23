<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode',
        'title',
        'shortDescription',
        'category',
        'tags',
        'owner',
        'ownerContact',
        'publicAddress',
        'tradeLicense',
        'logoUrl',
        'status'
    ];
}
