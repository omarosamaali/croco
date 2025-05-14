<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'main_category_id',
        'name_ar',
        'name_en',
        'price',
        'duration',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'duration' => 'integer',
    ];

    /**
     * Get the main category that owns the subcategory.
     */
    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    /**
     * العلاقة مع الاشتراكات
     * علاقة العديد للعديد (many-to-many)
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_sub_category')
            ->withTimestamps();
    }
}
