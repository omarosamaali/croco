<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Subscriber;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    // Process card payment directly
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

            Log::info('Card payment processed', [
                'subscriber_id' => $subscriber_id,
                'amount' => $subscriber->price,
                'card_last4' => substr($request->card_number, -4)
            ]);

            $subscriber->status = 'active';
            $subscriber->payment_status = 'paid';
            $subscriber->payment_date = now();
            if (empty($subscriber->activation_code)) {
                $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            }
            $subscriber->save();

            // Send payment confirmation email
            Mail::to($subscriber->email)->send(new PaymentConfirmation($subscriber, $lang));

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

    // PayPal payment method
    public function paypal(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::findOrFail($subscriber_id);
        $type = $request->query('type', 'account');

        if ($type === 'card') {
            $request->validate([
                'card_holder_name' => 'required|string|max:255',
                'card_type' => 'required|in:visa,mastercard,discover,amex',
                'card_number' => 'required|string|min:16|max:16',
                'card_expiry' => 'required|date_format:m/Y',
                'card_cvv' => 'required|string|min:3|max:4',
            ]);

            Log::info('Card Details', [
                'card_holder_name' => $request->card_holder_name,
                'card_type' => $request->card_type,
                'card_last4' => substr($request->card_number, -4),
            ]);
        }

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            Log::info('PayPal Token Retrieved', ['subscriber_id' => $subscriber_id]);

            $order = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $subscriber->price,
                        ],
                        'description' => 'Subscription for ' . $subscriber->game->name,
                    ],
                ],
                'application_context' => [
                    'return_url' => route('payment.success', ['lang' => $lang, 'subscriber_id' => $subscriber_id]),
                    'cancel_url' => route('payment.cancel', ['lang' => $lang, 'subscriber_id' => $subscriber_id]),
                ],
            ];

            $response = $provider->createOrder($order);
            Log::info('PayPal Create Order Response', ['order_id' => $response['id'] ?? 'No order ID']);

            if (isset($response['id']) && $response['id']) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        Log::info('Redirecting to PayPal Approval URL', ['url' => $link['href']]);
                        return response()->json(['redirect' => $link['href']]);
                    }
                }
                Log::error('No PayPal approval link found');
            } else {
                Log::error('Invalid PayPal response', $response);
            }
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            return response()->json(['error' => 'PayPal Error'], 500);
        }

        return response()->json(['error' => 'Something went wrong with PayPal'], 500);
    }

    // Payment success page
    public function success(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('game')->findOrFail($subscriber_id);
        $payment_id = $request->input('payment_id');

        // If this is a PayPal callback and there's a PayPal order ID
        if ($request->has('token') && $subscriber->payment_status != 'paid') {
            try {
                // Initialize PayPal
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));

                // Capture the PayPal order
                $response = $provider->capturePaymentOrder($request->token);
                Log::info('PayPal Capture Response', $response);

                // Check if payment was captured
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    // Create transaction record
                    $transaction = new PaymentTransaction();
                    $transaction->subscriber_id = $subscriber_id;
                    $transaction->amount = $subscriber->price;
                    $transaction->payment_method = 'paypal';
                    $transaction->transaction_id = $response['id'];
                    $transaction->status = 'completed';
                    $transaction->save();

                    // Update subscriber status
                    $subscriber->status = 'active';
                    $subscriber->payment_status = 'paid';
                    $subscriber->payment_date = now();
                    if (empty($subscriber->activation_code)) {
                        $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
                    }
                    $subscriber->save();

                    // Send payment confirmation email
                    Mail::to($subscriber->email)->send(new PaymentConfirmation($subscriber, $lang));

                    $payment_id = $response['id'];
                } else {
                    return redirect()->route('subscriber.confirm', [
                        'lang' => $lang,
                        'subscriber_id' => $subscriber_id
                    ])->with('error', __('Payment verification failed. Please try again.'));
                }
            } catch (\Exception $e) {
                Log::error('PayPal Capture Error: ' . $e->getMessage());
                return redirect()->route('subscriber.confirm', [
                    'lang' => $lang,
                    'subscriber_id' => $subscriber_id
                ])->with('error', __('Payment verification failed. Please try again.'));
            }
        }

        // Verify that payment was actually made
        if ($subscriber->payment_status != 'paid') {
            return redirect()->route('subscriber.confirm', [
                'lang' => $lang,
                'subscriber_id' => $subscriber_id
            ])->with('error', __('Payment has not been completed. Please complete the payment process.'));
        }

        // Pass subscription data to the success view
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

    /**
     * Show bank transfer form
     */
    public function showTransferForm($lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('game')->findOrFail($subscriber_id);

        return view('payment.transfer', compact('subscriber', 'lang'));
    }

    /**
     * Process bank transfer
     */
    public function storeTransfer(Request $request, $lang, $subscriber_id)
    {
        $request->validate([
            'transfer_image' => 'required|image|max:2048',
        ]);

        $subscriber = Subscriber::findOrFail($subscriber_id);

        try {
            // Store the transfer receipt image
            $imagePath = $request->file('transfer_image')->store('transfer_receipts', 'public');

            // Create payment transaction record
            $transaction = new PaymentTransaction();
            $transaction->subscriber_id = $subscriber_id;
            $transaction->amount = $subscriber->price;
            $transaction->payment_method = 'bank_transfer';
            $transaction->transaction_id = 'transfer_' . Str::random(16);
            $transaction->receipt_image = $imagePath;
            $transaction->status = 'pending'; // Bank transfers need manual verification
            $transaction->save();

            // Update subscriber status to pending
            $subscriber->status = 'pending';
            $subscriber->payment_status = 'pending';
            $subscriber->payment_date = now();

            // Generate activation code if not already generated
            if (empty($subscriber->activation_code)) {
                $subscriber->activation_code = $subscriber->id . '-' . substr(md5($subscriber->email), 0, 6);
            }

            $subscriber->save();

            // Send payment confirmation email
            Mail::to($subscriber->email)->send(new PaymentConfirmation($subscriber, $lang));

            // Show pending payment success message
            return view('payment.transfer_success', compact('subscriber', 'lang'));
        } catch (\Exception $e) {
            Log::error('Transfer processing error: ' . $e->getMessage());
            return back()->with('error', __('An error occurred while processing your transfer. Please try again.'));
        }
    }
}
