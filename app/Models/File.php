<?php

namespace App\Models;

use Storage;
use Illuminate\Support\Str;
use App\Models\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory, RelatesToTeams;

    protected $fillable = ['name', 'size', 'path'];

    public function sizeForHumans()
    {
        $bytes = $this->size;

        $units = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1025;
        }

        return round($bytes, 2) . $units[$i];
    }

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });

        static::deleting(function ($model) {
            Storage::disk('local')->delete($model->path);
        });
    }
}
