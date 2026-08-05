<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('title', 'display_price')]
class WaxingItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'waxing_group_id',
        'key',
        'title',
        'amount',
        'currency',
        'display_price',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(WaxingGroup::class, 'waxing_group_id');
    }
}
