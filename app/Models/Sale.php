<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
    'slip_no',
    'customer_name',
    'sold_by',
    'subtotal',
    'tax_rate',
    'tax_amount',
    'discount_amount',
    'discount_value',
    'discount_type',
    'total_amount',
    'notes',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class,'sold_by');
    }
}

