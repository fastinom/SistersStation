<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'store_description',
        'store_logo',
        'store_banner',
        'business_email',
        'business_phone',
        'business_address',
        'tax_id',
        'business_license',
        'verification_status',
        'rejection_reason',
        'commission_rate',
        'total_sales',
        'total_products',
        'rating',
        'review_count',
        'is_featured',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'rating' => 'decimal:2',
        'verified_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user that owns the seller.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the seller.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the order items for the seller.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the reviews for the seller.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if seller is verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved' && $this->verified_at !== null;
    }

    /**
     * Update seller statistics.
     */
    public function updateStatistics(): void
    {
        $this->total_products = $this->products()->where('status', 'active')->count();
        $this->total_sales = $this->orderItems()->sum('total_price');
        
        $avgRating = $this->reviews()->avg('rating');
        $this->rating = $avgRating ? round($avgRating, 2) : 0;
        $this->review_count = $this->reviews()->count();
        
        $this->save();
    }

    /**
     * Get active products count.
     */
    public function getActiveProductsCount(): int
    {
        return $this->products()->where('status', 'active')->count();
    }

    /**
     * Get monthly sales.
     */
    public function getMonthlySales(): float
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            })
            ->sum('total_price');
    }

    /**
     * Get pending orders count.
     */
    public function getPendingOrdersCount(): int
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['pending', 'confirmed']);
            })
            ->count();
    }
}
