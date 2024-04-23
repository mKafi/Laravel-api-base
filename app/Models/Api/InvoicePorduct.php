<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePorduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoiceId',
        'productId',       
        'itemTitle',
        'itemModel',
        'qty',
        'unitPrice',
        'itemTax',
        'status'
    ];
}
