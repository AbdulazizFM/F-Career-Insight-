<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'COMPLAINT';
    protected $primaryKey = 'complaint_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'evaluation_id',
        'title',
        'description',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'resolved_by', 'admin_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id', 'evaluation_id');
    }
}
