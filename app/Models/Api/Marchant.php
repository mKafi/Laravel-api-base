<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marchant extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode',
        'owner',
        'organization',
        'description',
        'contact',
        'comment',            
        'status'
    ];
}
