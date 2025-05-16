<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscriber;

class PaymentConfirmation extends Mailable implements ShouldQueue
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
        return $this->to($this->subscriber->email) // هنا تحدد المستلم باستخدام بريد المشترك
            ->view('emails.payment_confirmation')
            ->subject($this->lang === 'ar' ? 'تأكيد الدفع' : 'Payment Confirmation');
    }
}
