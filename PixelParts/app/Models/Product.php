<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'description',
        'price',
        'discount_percentage',
        'stock',
        'image',
    ];

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage && $this->discount_percentage > 0) {
            return $this->price * (1 - ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->discount_percentage && $this->discount_percentage > 0) {
            return $this->price * ($this->discount_percentage / 100);
        }
        return 0;
    }

      protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function compatibility()
    {
        return $this->hasMany(ProductCompatibility::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
