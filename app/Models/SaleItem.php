<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id','part_id','sold_price','quantity','line_total'];

    public function part()
    {
        return $this->belongsTo(\App\Models\Part::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
