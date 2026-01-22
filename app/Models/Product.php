<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'brand',
        'price',
        'compare_price',
        'cost_price',
        'weight',
        'dimensions',
        'track_quantity',
        'quantity',
        'min_quantity',
        'max_quantity',
        'requires_shipping',
        'tax_rate',
        'taxable',
        'status',
        'is_featured',
        'is_digital',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'dimensions' => 'array',
        'track_quantity' => 'boolean',
        'requires_shipping' => 'boolean',
        'taxable' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Get the seller that owns the product.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        // Temporarily disabled to prevent database queries during UI testing
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the wishlist items for the product.
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withPivot('variant_id')
            ->withTimestamps();
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        if (!$this->track_quantity) {
            return true;
        }
        
        return $this->quantity > 0;
    }

    /**
     * Get the price after discount.
     */
    public function getDiscountedPrice(): float
    {
        return $this->compare_price && $this->compare_price > $this->price 
            ? $this->price 
            : $this->compare_price ?? $this->price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentage(): ?int
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }
        
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    /**
     * Get the average rating.
     */
    public function getAverageRating(): float
    {
        if (isset($this->attributes['reviews_avg_rating'])) {
            return (float) ($this->attributes['reviews_avg_rating'] ?? 0);
        }

        return (float) ($this->reviews()->where('is_approved', true)->avg('rating') ?? 0);
    }

    /**
     * Get the total number of reviews.
     */
    public function getReviewCount(): int
    {
        if (isset($this->attributes['reviews_count'])) {
            return (int) ($this->attributes['reviews_count'] ?? 0);
        }

        return (int) $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Get the total sold quantity.
     */
    public function getTotalSold(): int
    {
        if (isset($this->attributes['order_items_sum_quantity'])) {
            return (int) ($this->attributes['order_items_sum_quantity'] ?? 0);
        }

        return (int) ($this->orderItems()->sum('quantity') ?? 0);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('track_quantity', false)
              ->orWhere('quantity', '>', 0);
        });
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Get the URL for the product.
     */
    public function getUrl(): string
    {
        return route('products.show', $this->slug);
    }

    /**
     * Get the primary image URL.
     */
    public function getPrimaryImageUrl(): string
    {
        $images = $this->relationLoaded('images') ? $this->images : null;

        $primary = $images
            ? $images->where('is_primary', true)->first() ?? $images->first()
            : $this->images()->where('is_primary', true)->first() ?? $this->images()->first();

        if ($primary) {
            return $primary->image_url;
        }

        $productName = urlencode($this->name ?? 'product');
        return "https://via.placeholder.com/400x400/f0f0f0/666?text={$productName}";
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        return $this->getPrimaryImageUrl();
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        return $this->getDiscountPercentage();
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->getAverageRating();
    }

    public function getReviewCountAttribute(): int
    {
        return $this->getReviewCount();
    }
}
