<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @method static \Database\Factories\CategoryFactory factory()
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Clear cache when category is created, updated, or deleted
        static::saved(function () {
            Cache::forget('categories.all');
        });

        static::deleted(function () {
            Cache::forget('categories.all');
        });
    }

    /**
     * Get the news posts for the category.
     */
    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class);
    }
}
