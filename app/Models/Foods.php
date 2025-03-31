<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'price_afterdiscount',
        'percent',
        'is_promo',
        'category_id',
    ];

    /**
     * Get the category that owns the food.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
