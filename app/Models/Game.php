<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'country',
        'main_category',
        'image',
        'description_ar',
        'description_en',
        'registration_date',
        'expiry_date',
        'username',
        'password',
        'dns_servers',
        'dns_expiry_date',
        'activation_code',
        'status',
    ];

    protected $casts = [
        'dns_servers' => 'array',
        'description_ar' => 'array',
        'description_en' => 'array',
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'dns_expiry_date' => 'date',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category');
    }

    
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'game_sub_category')
            ->withTimestamps();
    }
}
