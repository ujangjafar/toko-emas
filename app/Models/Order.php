<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
        'address',
        'phone',
        'email',
        'notes',
        'order_date',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank',
            'card' => 'Kartu Kredit/Debit'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}