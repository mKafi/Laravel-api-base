<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Ramsey\Uuid\v1;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode',
        'parentId',
        'name',
        'title',
        'status'
    ];
}
