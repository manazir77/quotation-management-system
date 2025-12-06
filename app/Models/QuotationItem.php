<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'gst_percentage',
        'gst_amount',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $basePrice = $model->unit_price * $model->quantity;
            $model->gst_amount = $basePrice * ($model->gst_percentage / 100);
            $model->total_price = $basePrice + $model->gst_amount;
        });
    }
}

