<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'price',
        'image',
        'description_ar',
        'description_en',
        'is_active',
        'order',
    ];

    protected $casts = [
        'description_ar' => 'array',
        'description_en' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الخطط
     * علاقة العديد للعديد (many-to-many)
     */
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'plan_sub_category')
            ->withTimestamps();
    }
}
