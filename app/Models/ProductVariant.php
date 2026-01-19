<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_price',
        'cost_price',
        'weight',
        'inventory_quantity',
        'inventory_policy',
        'inventory_tracking',
        'requires_shipping',
        'taxable',
        'position',
        'option1',
        'option2',
        'option3',
        'image_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'inventory_quantity' => 'integer',
        'inventory_tracking' => 'boolean',
        'requires_shipping' => 'boolean',
        'taxable' => 'boolean',
        'position' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function image()
    {
        return $this->belongsTo(ProductImage::class, 'image_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
