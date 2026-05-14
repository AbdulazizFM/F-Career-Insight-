<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'REPORT';
    protected $primaryKey = 'report_id';

    public $timestamps = false;

    protected $fillable = [
        'generated_by',
        'report_type',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'generated_by', 'admin_id');
    }
}
