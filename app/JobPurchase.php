<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPurchase extends Model
{
    protected $table = 'JOBPURCHASE';
    protected $primaryKey = 'purchase_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sub_major_id',
        'price',
        'purchase_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function subMajor()
    {
        return $this->belongsTo(SubMajor::class, 'sub_major_id', 'sub_major_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'purchase_id', 'purchase_id');
    }
}
