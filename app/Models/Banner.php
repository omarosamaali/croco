<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Banner extends Model
{
    protected $fillable = ['start_date', 'image_path', 'expiration_date', 'is_active', 'location'];

    protected $casts = [
        'image_path' => 'string',
        'location' => 'string',
        'start_date' => 'date',
        'expiration_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active banners.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include non-expired banners.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('expiration_date', '>=', Carbon::now());
    }

    /**
     * Scope a query to only include active and non-expired banners.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->notExpired();
    }
}
