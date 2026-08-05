<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('slug', 'title', 'display_price', 'duration', 'summary', 'benefits', 'details', 'skin_type', 'note')]
class Service extends Model
{
    use HasTranslations;

    protected $fillable = [
        'service_category_id',
        'key',
        'slug',
        'title',
        'display_price',
        'price_from',
        'duration',
        'summary',
        'benefits',
        'details',
        'skin_type',
        'note',
        'image',
        'is_featured',
        'featured_sort_order',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'is_featured' => 'boolean',
            'featured_sort_order' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<ServiceCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * @return HasMany<ServicePrice, $this>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ServicePrice::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return BelongsToMany<Specialist, $this>
     */
    public function specialists(): BelongsToMany
    {
        return $this->belongsToMany(Specialist::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order')
            ->orderBy('specialists.id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    #[Scope]
    protected function featured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
