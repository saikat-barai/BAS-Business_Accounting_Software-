<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'invoice_number',
        'invoice_date',
        'subtotal',
        'tax',
        'tax_ammount',
        'discount',
        'total',
        'status',
        'paid_amount'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // protected $casts = [
    //     'invoice_date' => 'date',
    // ];
}
