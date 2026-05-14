<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'SUBSCRIPTION';
    protected $primaryKey = 'subscription_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'plan_type',
        'price',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'subscription_id', 'subscription_id');
    }

    public function scopeActiveNow($query)
    {
        return $query
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->whereDate('start_date', '<=', Carbon::today()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString());
    }

    public function isActiveNow(): bool
    {
        if (strtolower((string) $this->status) !== 'active') {
            return false;
        }

        $today = Carbon::today();
        $start = $this->start_date ? Carbon::parse($this->start_date) : null;
        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

        if (! $start || ! $end) {
            return false;
        }

        return $today->between($start->startOfDay(), $end->endOfDay());
    }
}
