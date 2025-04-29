<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Nicolaslopezj\Searchable\SearchableTrait;

class Tag extends Model
{
    use HasFactory, Sluggable, SearchableTrait;
    protected $guarded = [];

    protected $searchable = [
        'columns' => [
            'tags.name' => 10, // Higher weight for name
        ]
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }
}
