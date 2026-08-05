<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('eyebrow', 'title', 'lead', 'text', 'primary_label', 'secondary_label', 'stats')]
class HomeHeroBlock extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'eyebrow',
        'title',
        'lead',
        'text',
        'primary_label',
        'secondary_label',
        'stats',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
