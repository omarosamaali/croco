<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscriber;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $lang;

    public function __construct(Subscriber $subscriber, string $lang)
    {
        $this->subscriber = $subscriber;
        $this->lang = $lang;
    }

    public function build()
    {
        return $this->view('emails.payment_confirmation')
            ->subject($this->lang === 'ar' ? 'تأكيد الدفع' : 'Payment Confirmation');
    }
}
