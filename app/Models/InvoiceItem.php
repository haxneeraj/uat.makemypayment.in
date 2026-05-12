<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',

        'description',
        'hsn_sac_code',

        'quantity',
        'unit_price',

        'gst_type',
        'gst_rate',

        'base_amount',
        'gst_amount',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
