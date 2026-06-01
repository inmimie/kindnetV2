<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'application_id',
        'amount',
        'transaction_id',
        'status',
        'payment_gateway',
        'payment_method',
        'gateway_reference',
        'notes',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get gateway display name.
     */
    public function getGatewayDisplayNameAttribute(): string
    {
        return match ($this->payment_gateway) {
            'fpx' => 'FPX Online Banking',
            'duitnow' => 'DuitNow Transfer',
            'toyyibpay' => 'ToyyibPay',
            'billplz' => 'Billplz',
            'bank_transfer' => 'Bank Transfer (Manual)',
            default => ucfirst($this->payment_gateway),
        };
    }

    /**
     * Get gateway badge color classes.
     */
    public function getGatewayBadgeClassAttribute(): string
    {
        return match ($this->payment_gateway) {
            'fpx' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'duitnow' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400',
            'toyyibpay' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'billplz' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            'bank_transfer' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
