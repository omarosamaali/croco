<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $table = 'main_categories';

    protected $fillable = [
        'name_ar',
        'name_en',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    // إضافة accessor للحصول على مسار الصورة
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
