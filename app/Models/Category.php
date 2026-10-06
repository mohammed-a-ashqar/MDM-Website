<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (! filled($category->slug) && filled($category->name)) {
                $category->slug = static::makeUniqueSlug(
                    Str::slug($category->name),
                    $category->exists ? $category->getKey() : null
                );
            }
        });

        static::saved(fn () => Cache::deleteMultiple(['nav_categories', 'footer_categories']));
        static::deleted(fn () => Cache::deleteMultiple(['nav_categories', 'footer_categories']));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $base = Str::slug($base) ?: 'category';

        $slug = $base;
        $i = 2;
        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class)->orderBy('sort_order')->orderBy('name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeSubcategories(): HasMany
    {
        return $this->subcategories()->where('is_active', true);
    }
}
