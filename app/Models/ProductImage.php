<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path;

        if (!$path) {
            return asset('images/placeholder-product.jpg');
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        if (str_starts_with($path, 'products/')) {
            return asset('storage/' . $path);
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset($path);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
