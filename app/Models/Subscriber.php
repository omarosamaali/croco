<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'duration',
        'country',
        'name',
        'email',
        'phone',
        'status',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
