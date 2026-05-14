<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMajor extends Model
{
    protected $table = 'SUBMAJOR';
    protected $primaryKey = 'sub_major_id';

    public $timestamps = false;

    protected $fillable = [
        'major_id',
        'sub_major_name',
        'description',
        'image_path',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'major_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'sub_major_id', 'sub_major_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'sub_major_id', 'sub_major_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = trim((string) $this->image_path);
        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
