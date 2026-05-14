<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'ADMIN';
    protected $primaryKey = 'admin_id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'access_level',
        'department',
        'security_clearance_level',
        'two_factor_enabled',
        'last_login_ip',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'security_clearance_level' => 'integer',
    ];

    public function reportFlagsReviewed()
    {
        return $this->hasMany(ReportFlag::class, 'reviewed_by', 'admin_id');
    }

    public function complaintsResolved()
    {
        return $this->hasMany(Complaint::class, 'resolved_by', 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
