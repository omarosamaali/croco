<?php

// app/Models/News.php
// app/Models/News.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class News extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image_path',
        'secondary_image', // Add this new field
        'author',
        'comments_count'
    ];
}