<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmation; // هذه لا تزال مستخدمة لإرسال البريد الأولي
use Illuminate\Support\Facades\Mail; // هذه لا تزال مستخدمة لإرسال البريد الأولي

class PaymentController extends Controller
{
    public function processPaypal(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::findOrFail($subscriber_id);
        try {
            $order_id = $request->input('orderID');
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->showOrderDetails($order_id);
            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                // حالة المشترك بعد الدفع، يمكنك تركها active أو تغييرها حسب سير عملك الدقيق
                // إذا كانت pending_dns، فهذا منطقي ليعرف الأدمن أنه بانتظار التفعيل النهائي
                $subscriber->status = 'active'; // أو 'pending_dns'
                $subscriber->payment_status = 'paid';
                $subscriber->payment_date = now();

                // **BEGINNING OF CODE TO REMOVE**
                // هذا الكود هو الذي ينشئ activation_code تلقائيًا
                // if (empty($subscriber->activation_code)) {
                //     $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
                // }
                // **END OF CODE TO REMOVE**

                $subscriber->save(); // حفظ التغييرات الأخرى (الحالة، تاريخ الدفع)

                $transaction = new PaymentTransaction();
                $transaction->subscriber_id = $subscriber_id;
                $transaction->amount = $subscriber->price;
                $transaction->payment_method = 'paypal';
                $transaction->transaction_id = $response['id'];
                $transaction->status = 'completed';
                $transaction->save();

                // **منطق إرسال البريد موجود هنا كما كان**
                // هذا هو البريد الأولي الذي يجب أن يخبر المستخدم بأن الطلب قيد المراجعة
                if (!empty($subscriber->email) && is_string($subscriber->email)) {
                    try {
                        Log::info('Attempting to queue email to: ' . $subscriber->email, [
                            'subscriber_id' => $subscriber_id,
                            'lang' => $lang
                        ]);
                        // يتم إرسال PaymentConfirmation Mailable هنا
                        // يجب أن يتأكد هذا الـ Mailable (أو الـ View الخاص به) من عدم عرض activation_code إذا كان فارغاً
                        Mail::queue(new PaymentConfirmation($subscriber, $lang), [], function ($message) use ($subscriber) {
                            $message->to($subscriber->email)->onQueue('default');
                        });
                        Log::info('Payment confirmation email queued for: ' . $subscriber->email);
                    } catch (\Exception $e) {
                        Log::error('Failed to queue payment confirmation email: ' . $e->getMessage(), [
                            'subscriber_id' => $subscriber_id,
                            'email' => $subscriber->email,
                            'error_trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    Log::warning('Email is invalid or missing for subscriber: ' . $subscriber_id);
                }

                return redirect()->route('payment.success', [
                    'lang' => $lang,
                    'subscriber_id' => $subscriber_id,
                    'payment_id' => $response['id']
                ]);
            } else {
                Log::error('PayPal payment not completed', ['response' => $response]);
                return redirect()->route('payment.cancel', [
                    'lang' => $lang,
                    'subscriber_id' => $subscriber_id
                ])->with('error', 'Payment was not completed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' + $e->getMessage());
            return redirect()->route('payment.cancel', [
                'lang' => $lang,
                'subscriber_id' => $subscriber_id
            ])->with('error', 'An error occurred. Please try again.');
        }
    }

    public function success(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('mainCategory', 'subCategory')->findOrFail($subscriber_id);
        $payment_id = $request->query('payment_id');

        Log::info('Payment success accessed', [
            'subscriber_id' => $subscriber_id,
            'payment_id' => $payment_id
        ]);

        return view('payment.success', compact('subscriber', 'lang', 'payment_id'));
    }

    public function cancel($lang, $subscriber_id)
    {
        $subscriber = Subscriber::findOrFail($subscriber_id);
        return redirect()->route('subscriber.confirm', [
            'lang' => $lang,
            'subscriber_id' => $subscriber_id
        ])->with('error', __('Payment was cancelled. Please try again.'));
    }

    public function paypal(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('subCategory')->findOrFail($subscriber_id);
        return view('subscriber.confirm', [
            'subscriber' => $subscriber,
            'price' => $subscriber->subCategory->price ?? 0,
            'lang' => $lang
        ]);
    }

    public function processCardPayment(Request $request, $lang, $subscriber_id)
    {
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:16|max:16',
            'card_expiry' => 'required|date_format:m/Y',
            'card_cvc' => 'required|string|min:3|max:4',
        ]);

        $subscriber = Subscriber::findOrFail($subscriber_id);

        try {
            $transaction = new PaymentTransaction();
            $transaction->subscriber_id = $subscriber_id;
            $transaction->amount = $subscriber->price;
            $transaction->payment_method = 'card';
            $transaction->transaction_id = 'card_' . Str::random(16);
            $transaction->status = 'completed';
            $transaction->save();

            // حالة المشترك بعد الدفع
            $subscriber->status = 'active'; // أو 'pending_dns'
            $subscriber->payment_status = 'paid';
            $subscriber->payment_date = now();

            // **BEGINNING OF CODE TO REMOVE**
            // هذا الكود هو الذي ينشئ activation_code تلقائيًا
            // if (empty($subscriber->activation_code)) {
            //     $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            // }
            // **END OF CODE TO REMOVE**

            $subscriber->save(); // حفظ التغييرات الأخرى

            // **منطق إرسال البريد موجود هنا كما كان**
            // هذا هو البريد الأولي الذي يجب أن يخبر المستخدم بأن الطلب قيد المراجعة
            if (!empty($subscriber->email) && is_string($subscriber->email)) {
                try {
                    Log::info('Attempting to queue email to: ' . $subscriber->email, [
                        'subscriber_id' => $subscriber_id,
                        'lang' => $lang
                    ]);
                    // يتم إرسال PaymentConfirmation Mailable هنا
                    // يجب أن يتأكد هذا الـ Mailable (أو الـ View الخاص به) من عدم عرض activation_code إذا كان فارغاً
                    Mail::queue(new PaymentConfirmation($subscriber, $lang), [], function ($message) use ($subscriber) {
                        $message->to($subscriber->email)->onQueue('default');
                    });
                    Log::info('Payment confirmation email queued for: ' . $subscriber->email);
                } catch (\Exception $e) {
                    Log::error('Failed to queue payment confirmation email: ' + $e->getMessage(), [
                        'subscriber_id' => $subscriber_id,
                        'email' => $subscriber->email,
                        'error_trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::warning('Email is invalid or missing for subscriber: ' + $subscriber_id);
            }


            return redirect()->route('payment.success', [
                'lang' => $lang,
                'subscriber_id' => $subscriber_id,
                'payment_id' => $transaction->transaction_id
            ])->with('success', __('Payment successful!'));
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' + $e->getMessage());
            return back()->with('error', __('An error occurred. Please try again.'))->withInput();
        }
    }

    public function showTransferForm($lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('mainCategory')->findOrFail($subscriber_id);
        return view('payment.transfer', compact('subscriber', 'lang'));
    }

    public function storeTransfer(Request $request, $lang, $subscriber_id)
    {
        $request->validate([
            'transfer_image' => 'required|image|max:2048',
        ]);

        $subscriber = Subscriber::findOrFail($subscriber_id);

        try {
            $imagePath = $request->file('transfer_image')->store('transfer_receipts', 'public');

            $transaction = new PaymentTransaction();
            $transaction->subscriber_id = $subscriber_id;
            $transaction->amount = $subscriber->price;
            $transaction->payment_method = 'bank_transfer';
            $transaction->transaction_id = 'transfer_' . Str::random(16);
            $transaction->receipt_image = $imagePath;
            $transaction->status = 'pending'; // حالة المعاملة هنا pending لأنها تتطلب مراجعة يدوية
            $transaction->save();

            // حالة المشترك بعد التحويل، غالباً ستكون قيد المراجعة
            $subscriber->status = 'pending_review'; // أو 'pending_dns'
            $subscriber->payment_status = 'pending';
            $subscriber->payment_date = now();

            // **BEGINNING OF CODE TO REMOVE**
            // هذا الكود هو الذي ينشئ activation_code تلقائيًا
            // if (empty($subscriber->activation_code)) {
            //     $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            // }
            // **END OF CODE TO REMOVE**

            $subscriber->save(); // حفظ التغييرات الأخرى

            // **منطق إرسال البريد هنا أيضاً إذا أردت إرسال تأكيد أولي للتحويل البنكي**
            // هذا البريد يجب أن يخبر المستخدم أن طلب التحويل قيد المراجعة
            if (!empty($subscriber->email) && is_string($subscriber->email)) {
                try {
                    Log::info('Attempting to queue initial transfer confirmation email (under review) to: ' . $subscriber->email, [
                        'subscriber_id' => $subscriber_id,
                        'lang' => $lang
                    ]);
                    // يتم إرسال PaymentConfirmation Mailable هنا
                    // يجب أن يتأكد هذا الـ Mailable (أو الـ View الخاص به) من عدم عرض activation_code إذا كان فارغاً
                    Mail::queue(new PaymentConfirmation($subscriber, $lang), [], function ($message) use ($subscriber) {
                        $message->to($subscriber->email)->onQueue('default');
                    });
                    Log::info('Initial transfer confirmation email (under review) queued for: ' . $subscriber->email);
                } catch (\Exception $e) {
                    Log::error('Failed to queue initial transfer confirmation email: ' + $e->getMessage(), [
                        'subscriber_id' => $subscriber_id,
                        'email' => $subscriber->email,
                        'error_trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::warning('Email is invalid or missing for subscriber when attempting to send initial transfer confirmation: ' + $subscriber_id);
            }


            return view('payment.transfer_success', compact('subscriber', 'lang'));
        } catch (\Exception $e) {
            Log::error('Transfer processing error: ' + $e->getMessage());
            return back()->with('error', __('An error occurred while processing your transfer. Please try again.'));
        }
    }
}
