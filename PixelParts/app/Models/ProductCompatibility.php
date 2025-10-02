<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'compatible_with',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
