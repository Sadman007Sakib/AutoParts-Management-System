<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PartImage extends Model
{
    use HasFactory;

    // If your table has different naming or columns adjust fillable as needed
    protected $fillable = [
        'part_id',
        'path',       // storage path like 'parts/filename.jpg'
        'filename',   // original filename
        'uploaded_by',
    ];

    /**
     * Booted model events — ensure file removed from disk when record is permanently deleted.
     */
    protected static function booted()
    {
        static::deleting(function (PartImage $image) {
            // If model uses SoftDeletes, then isForceDeleting() exists and will be true only on forceDelete.
            // If not using SoftDeletes, isForceDeleting doesn't exist; treat it as permanent delete.
            if (method_exists($image, 'isForceDeleting')) {
                // If it's a soft delete (not forced), skip physical file deletion for safety
                if (! $image->isForceDeleting()) {
                    return;
                }
            }

            // Delete the file from the storage disk (public) if it exists
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                try {
                    Storage::disk('public')->delete($image->path);
                } catch (\Throwable $e) {
                    // Log and continue — do not halt deletion if file removal fails
                    \Log::warning("Failed deleting part image file during model delete", [
                        'image_id' => $image->id,
                        'path' => $image->path,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }

    /**
     * Relationship: an image belongs to a part
     */
    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
