<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
        protected $table = 'privacy_policy'; // Specify the table name

    protected $fillable = ['content_ar', 'content_en'];
}