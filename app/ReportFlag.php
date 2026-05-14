<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFlag extends Model
{
    protected $table = 'REPORT_FLAG';
    protected $primaryKey = 'report_id';

    public $timestamps = false;

    protected $fillable = [
        'reviewed_by',
        'reporter_id',
        'target_type',
        'target_id',
        'reason',
        'description',
        'status',
        'reviewed_at',
        'created_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by', 'admin_id');
    }
}
