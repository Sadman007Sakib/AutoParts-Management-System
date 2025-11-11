<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// Uncomment SoftDeletes if you use soft deletes on parts
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory;
    use SoftDeletes; // safe to include; if you don't want soft deletes, remove this line and the trait import

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

    /**
     * Relationship: a part has many images
     */
    public function images()
    {
        return $this->hasMany(PartImage::class);
    }

    /**
     * Booted model events — ensure related images (and their files) are deleted
     * when the part is permanently deleted (forceDelete).
     *
     * Behavior:
     * - If soft deletes are used: on normal delete() (soft) files remain.
     * - On forceDelete(): related PartImage records are deleted (which triggers PartImage::deleting
     *   and removes files).
     * - If you do not use SoftDeletes, delete() will be treated as permanent and cleanup will run.
     */
    protected static function booted()
    {
        static::deleting(function (Part $part) {
            // If model uses SoftDeletes and this is a soft-delete, skip cleanup
            if (method_exists($part, 'isForceDeleting')) {
                if (! $part->isForceDeleting()) {
                    // soft delete: do not remove images/files yet
                    return;
                }
            }

            // For permanent deletion: delete all related images (this will fire their deleting event)
            foreach ($part->images()->get() as $img) {
                try {
                    $img->delete();
                } catch (\Throwable $e) {
                    \Log::warning("Failed deleting part->image during part deletion", [
                        'part_id' => $part->id,
                        'image_id' => $img->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }
}
