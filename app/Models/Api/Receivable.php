<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;
    protected $fillable = [
        'traderCode',
        'client',
        'address',
        'reference',
        'contact',
        'amount',
        'dueDate',
        'type',
        'comment',
        'status'
    ];
}
