<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AbandonedCart extends Model
{
    protected $fillable = [
        'user_id',
        'cart_data',
        'token',
        'email_sent_at',
        'recovered_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'email_sent_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            if (empty($cart->token)) {
                $cart->token = Str::random(64);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsEmailSent(): void
    {
        $this->update(['email_sent_at' => now()]);
    }

    public function markAsRecovered(): void
    {
        $this->update(['recovered_at' => now()]);
    }

    public function isRecovered(): bool
    {
        return !is_null($this->recovered_at);
    }

    public function emailSent(): bool
    {
        return !is_null($this->email_sent_at);
    }

    public function getTotalAmount(): float
    {
        $total = 0;
        foreach ($this->cart_data as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        return $total;
    }
}
