<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'foods_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Get the transaction that owns the transaction item.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the food associated with the transaction item.
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'foods_id');
    }
}