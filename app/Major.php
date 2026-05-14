<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'MAJOR';
    protected $primaryKey = 'major_id';

    public $timestamps = false;

    protected $fillable = [
        'major_name',
        'description',
        'image_path',
    ];

    public function subMajors()
    {
        return $this->hasMany(SubMajor::class, 'major_id', 'major_id');
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
