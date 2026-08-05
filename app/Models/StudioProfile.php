<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudioProfile extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'phone_href',
        'email',
        'address',
        'maps_url',
        'map_embed_url',
        'languages',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'languages' => 'array',
            'is_active' => 'boolean',
        ];
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
}
