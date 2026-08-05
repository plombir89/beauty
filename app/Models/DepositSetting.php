<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('eyebrow', 'title', 'text', 'note')]
class DepositSetting extends Model
{
    use HasTranslations;

    protected $fillable = [
        'amount',
        'currency',
        'payment_methods',
        'offsite_payment_methods',
        'eyebrow',
        'title',
        'text',
        'note',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_methods' => 'array',
            'offsite_payment_methods' => 'array',
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
