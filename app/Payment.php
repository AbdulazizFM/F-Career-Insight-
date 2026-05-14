<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'PAYMENT';
    protected $primaryKey = 'payment_id';

    public $timestamps = false;

    protected $fillable = [
        'subscription_id',
        'purchase_id',
        'amount',
        'transaction_id',
        'payment_method',
        'payment_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'subscription_id');
    }

    public function jobPurchase()
    {
        return $this->belongsTo(JobPurchase::class, 'purchase_id', 'purchase_id');
    }
}
