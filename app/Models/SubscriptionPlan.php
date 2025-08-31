<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'meeting_duration_limit',
        'max_participants',
        'storage_limit',
        'has_recording',
        'has_breakout_rooms',
        'has_admin_tools',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_recording' => 'boolean',
        'has_breakout_rooms' => 'boolean',
        'has_admin_tools' => 'boolean',
        'is_active' => 'boolean',
        'meeting_duration_limit' => 'integer',
        'max_participants' => 'integer',
        'storage_limit' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscriptions for this plan.
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->subscriptions()->where('status', 'active');
    }

    /**
     * Scope to get only active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if the plan is free.
     */
    public function isFree(): bool
    {
        return $this->price == 0 || $this->billing_cycle === 'free';
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        
        return '₦' . number_format($this->price, 0);
    }

    /**
     * Get meeting duration display.
     */
    public function getMeetingDurationDisplayAttribute(): string
    {
        if ($this->meeting_duration_limit === null) {
            return 'Unlimited';
        }
        
        return $this->meeting_duration_limit . ' minutes';
    }

    /**
     * Get storage display.
     */
    public function getStorageDisplayAttribute(): string
    {
        if ($this->storage_limit === null) {
            return 'Unlimited';
        }
        
        return $this->storage_limit . ' GB';
    }
}
