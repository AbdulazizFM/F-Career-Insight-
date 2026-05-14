<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    protected $table = 'MESSAGETHREAD';
    protected $primaryKey = 'thread_id';

    public $timestamps = false;

    protected $fillable = [
        'evaluation_id',
        'user_one_id',
        'user_two_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id', 'evaluation_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id', 'thread_id');
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id', 'user_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id', 'user_id');
    }
}
