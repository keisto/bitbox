<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory, RelatesToTeams;

    protected $fillable = ['name'];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
