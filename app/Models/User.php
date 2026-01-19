<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'date_of_birth',
        'bio',
        'avatar',
        'is_active',
        'email_verified',
        'phone_verified',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
    ];

    /**
     * Get the seller associated with the user.
     */
    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the wishlist items for the user.
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withPivot('variant_id')
            ->withTimestamps();
    }

    /**
     * Check if user is a seller.
     */
    public function isSeller(): bool
    {
        return $this->user_type === 'seller' || $this->hasRole('seller');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'customer' || $this->hasRole('customer');
    }

    /**
     * Get the default billing address.
     */
    public function getDefaultBillingAddress(): ?Address
    {
        return $this->addresses()->where('type', 'billing')->where('is_default', true)->first();
    }

    /**
     * Get the default shipping address.
     */
    public function getDefaultShippingAddress(): ?Address
    {
        return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first();
    }

    /**
     * Get cart total amount.
     */
    public function getCartTotal(): float
    {
        return $this->cartItems()->sum('total_price');
    }

    /**
     * Get cart item count.
     */
    public function getCartItemCount(): int
    {
        return $this->cartItems()->sum('quantity');
    }
}
