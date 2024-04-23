<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode',
        'name',
        'address',
        'phone',
        'father',
        'mother',
        'reference',
        'email',
        'nid',
        'socialMeadiaUrl',
        'profileInfo',
        'photUrl',
        'tags',
        'status'
    ];
}
