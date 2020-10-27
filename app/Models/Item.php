<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use App\Models\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Item extends Model
{
    use HasFactory, RelatesToTeams, HasRecursiveRelationships, Searchable;

    public $asYouType = true;

    protected $fillable = ['parent_id'];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });

        static::deleting(function ($model) {
            optional($model->category)->delete();
            $model->descendants->each->delete();
        });
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'name' => $this->category->name,
            'path' => $this->ancestorsAndSelf
                ->pluck('category.name')
                ->reverse()
                ->join('/'),
        ];
    }

    public function category()
    {
        return $this->morphTo();
    }

    //     public function children()
    //     {
    //         return $this->hasMany(Item::class, 'parent_id', 'id');
    //     }
    //
    //     public function parent()
    //     {
    //         return $this->belongsTo(Item::class, 'parent_id', 'id');
    //     }

    //     public function ancestors()
    //     {
    //         $ancestor = $this;
    //         $ancestors = collect();
    //
    //         while ($ancestor->parent) {
    //             $ancestor = $ancestor->parent;
    //             $ancestores->push($ancestor);
    //         }
    //
    //         return $ancestors;
    //     }
}
