<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'external_id',
        'checkout_link',
        'barcodes_id',
        'payment_method',
        'payment_status',
        'subtotal',
        'ppn',
        'total',
    ];

    /**
     * Get the barcode that owns the transaction.
     */
    public function barcode(): BelongsTo
    {
        return $this->belongsTo(Barcode::class, 'barcodes_id');
    }
}