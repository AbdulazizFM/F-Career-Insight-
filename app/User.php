<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'USER';
    protected $primaryKey = 'user_id';

    public $timestamps = true;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'account_status',
        'user_type',
        'job_title',
        'company',
        'years_experience',
    ];

    protected $hidden = [
        'password',
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'user_id', 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'user_id');
    }

    public function jobPurchases()
    {
        return $this->hasMany(JobPurchase::class, 'user_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'user_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id', 'user_id');
    }
}
