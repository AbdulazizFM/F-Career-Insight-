<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'EVALUATION';
    protected $primaryKey = 'evaluation_id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'sub_major_id',
        'role_id',
        'rating',
        'advantages',
        'disadvantages',
        'experience',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function subMajor()
    {
        return $this->belongsTo(SubMajor::class, 'sub_major_id', 'sub_major_id');
    }

    public function threads()
    {
        return $this->hasMany(MessageThread::class, 'evaluation_id', 'evaluation_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
