<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use HasFactory, Sluggable,SearchableTrait;

    protected $guarded = [];

    protected $searchable = [
        'columns' => [
            'products.name' => 10,
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

    public function featured()
    {
        return $this->featured ? 'Yes' : 'No';
    }

    public function scopeFeatured($query)
    {
        return $query->whereFeatured(true);
    }
    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }
    public function scopeHasQuantity($query)
    {
        return $query->where('quantity', '>', 0);
    }
    public function scopeActiveCategory($query)
    {
        return $query->whereHas('category', function ($query) {
            $query->whereStatus(1);
        });
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function category(): belongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function media(): MorphMany
    {
        return $this->MorphMany(Media::class, 'mediable');
    }
    public function firstMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')->orderBy('file_sort', 'asc');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }


}
