<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'saleId',        
        'invoiceCode',
        'subTotal',
        'tax',
        'previousDue',
        'discount',
        'grandTotal',
        'paid',
        'due',
        'nextPaymentDate',
        'comment',
        'status'
    ];
}
