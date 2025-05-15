<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function processPaypal(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('mainCategory', 'subCategory')->findOrFail($subscriber_id);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $order_id = $request->input('orderID');
            $response = $provider->capturePaymentOrder($order_id);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                // تحديث حالة المشترك
                $subscriber->status = 'active';
                $subscriber->payment_status = 'paid';
                $subscriber->payment_date = now();
                if (empty($subscriber->activation_code)) {
                    $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
                }
                $subscriber->save();

                // تسجيل المعاملة
                $transaction = new PaymentTransaction();
                $transaction->subscriber_id = $subscriber_id;
                $transaction->amount = $subscriber->price;
                $transaction->payment_method = 'paypal';
                $transaction->transaction_id = $response['id'];
                $transaction->status = 'completed';
                $transaction->save();

                // إرسال بريد التأكيد
                try {
                    Log::info('Attempting to send email to: ' . $subscriber->email, [
                        'subscriber_id' => $subscriber_id,
                        'lang' => $lang
                    ]);
                    Mail::to($subscriber->email)->send(new PaymentConfirmation($subscriber, $lang));
                    Log::info('Payment confirmation email sent to: ' . $subscriber->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage(), [
                        'subscriber_id' => $subscriber_id,
                        'email' => $subscriber->email,
                        'error_trace' => $e->getTraceAsString()
                    ]);
                }

                return response()->json(['success' => true]);
            } else {
                Log::error('PayPal payment not completed', ['response' => $response]);
                return response()->json(['success' => false, 'error' => 'Payment not completed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'PayPal error'], 500);
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
        $subscriber = Subscriber::findOrFail($subscriber_id);
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

            $subscriber->status = 'active';
            $subscriber->payment_status = 'paid';
            $subscriber->payment_date = now();
            if (empty($subscriber->activation_code)) {
                $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            }
            $subscriber->save();

            try {
                Log::info('Attempting to send email to: ' . $subscriber->email, [
                    'subscriber_id' => $subscriber_id,
                    'lang' => $lang
                ]);
                Mail::to($subscriber->email)->send(new PaymentConfirmation($subscriber, $lang));
                Log::info('Payment confirmation email sent to: ' . $subscriber->email);
            } catch (\Exception $e) {
                Log::error('Failed to send payment confirmation email: ' . $e->getMessage(), [
                    'subscriber_id' => $subscriber_id,
                    'email' => $subscriber->email,
                    'error_trace' => $e->getTraceAsString()
                ]);
            }

            return redirect()->route('payment.success', [
                'lang' => $lang,
                'subscriber_id' => $subscriber_id,
                'payment_id' => $transaction->transaction_id
            ])->with('success', __('Payment successful!'));
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
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
            $transaction->status = 'pending';
            $transaction->save();

            $subscriber->status = 'pending';
            $subscriber->payment_status = 'pending';
            $subscriber->payment_date = now();
            if (empty($subscriber->activation_code)) {
                $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            }
            $subscriber->save();

            return view('payment.transfer_success', compact('subscriber', 'lang'));
        } catch (\Exception $e) {
            Log::error('Transfer processing error: ' . $e->getMessage());
            return back()->with('error', __('An error occurred while processing your transfer. Please try again.'));
        }
    }
}
