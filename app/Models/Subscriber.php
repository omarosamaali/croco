<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_category_id',
        'sub_category_id',
        'country',
        'name',
        'email',
        'phone',
        'price',
        'status',
        'payment_status',
        'payment_date',
        'activation_code',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
