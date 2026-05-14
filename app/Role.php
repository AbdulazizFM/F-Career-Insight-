<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'ROLES';
    protected $primaryKey = 'role_id';

    public $timestamps = false;

    protected $fillable = [
        'sub_major_id',
        'role_name',
        'salary_range',
        'challenges',
    ];

    public function subMajor()
    {
        return $this->belongsTo(SubMajor::class, 'sub_major_id', 'sub_major_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'role_id', 'role_id');
    }
}
