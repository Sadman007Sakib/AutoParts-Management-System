<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'brand',
        'description',
        'cost_price',
        'sell_price',
        'current_quantity',
        'created_by',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];

    protected $appends = [
        'profit_per_item',
        'inventory_value',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function images()
    {
        return $this->hasMany(PartImage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getProfitPerItemAttribute()
    {
        return ($this->sell_price ?? 0) - ($this->cost_price ?? 0);
    }

    public function getInventoryValueAttribute()
    {
        return ($this->cost_price ?? 0) * ($this->current_quantity ?? 0);
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images->first();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeLowStock($query)
    {
        return $query->where('current_quantity', '<=', 5);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($part) {

            if (empty($part->sku)) {

                $prefix = strtoupper(substr($part->brand ?? 'GEN', 0, 3));

                $date = now()->format('Ymd');

                $last = self::where('sku', 'like', "{$prefix}-{$date}-%")
                    ->latest('id')
                    ->first();

                if ($last && preg_match('/-(\d+)$/', $last->sku, $m)) {
                    $next = str_pad(((int)$m[1]) + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $next = '0001';
                }

                $part->sku = "{$prefix}-{$date}-{$next}";
            }
        });

        static::deleting(function (Part $part) {

            if (method_exists($part, 'isForceDeleting')) {

                if (! $part->isForceDeleting()) {
                    return;
                }
            }

            foreach ($part->images as $img) {
                $img->delete();
            }
        });
    }
}