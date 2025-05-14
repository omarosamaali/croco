<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'subscriber_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'receipt_image',
    ];

    // Define relationships if needed
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
