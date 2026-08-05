<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('label', 'display_price', 'duration', 'note')]
class ServicePrice extends Model
{
    use HasTranslations;

    protected $fillable = [
        'service_id',
        'label',
        'amount',
        'amount_max',
        'currency',
        'display_price',
        'duration',
        'note',
        'sort_order',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'amount_max' => 'decimal:2',
            'sort_order' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
