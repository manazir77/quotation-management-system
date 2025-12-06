<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'quotation_number',
        'quotation_date',
        'quotation_time',
        'validity_date',
        'notes',
        'terms_conditions',
        'subtotal',
        'total_gst',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'validity_date' => 'date',
        'subtotal' => 'decimal:2',
        'total_gst' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public static function generateQuotationNumber(): string
    {
        $prefix = 'QT';
        $year = date('Y');
        $lastQuotation = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastQuotation) {
            $lastNumber = intval(substr($lastQuotation->quotation_number, -5));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $year . $newNumber;
    }

    public function calculateTotals(): void
    {
        $subtotal = 0;
        $totalGst = 0;

        foreach ($this->items as $item) {
            $subtotal += $item->unit_price * $item->quantity;
            $totalGst += $item->gst_amount;
        }

        $this->subtotal = $subtotal;
        $this->total_gst = $totalGst;
        $this->total_amount = $subtotal + $totalGst;
        $this->save();
    }
}

