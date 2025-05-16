<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentTransaction;
use App\Mail\PaymentConfirmation;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
// Import App


class SubscriberController extends Controller
{
    public function index(string $lang) // تقبل الـ lang لأن المسار يحتوي عليها
    {
        // تعيين اللغة
        \App::setLocale($lang);
        session(['language' => $lang]); // حفظ اللغة في الجلسة إذا لزم الأمر

        // جلب قائمة المشتركين من قاعدة البيانات
        $subscribers = Subscriber::all(); // يمكنك تعديل هذا الجلب حسب الحاجة (ترتيب، تصفية، تجزئة)

        // يمكنك تمرير الـ lang أيضاً إلى الـ view إذا كنت تستخدمه هناك
        return view('admin.games.index', compact('subscribers', 'lang')); // تفترض وجود view اسمه games.index
    }


    public function edit($lang, $game)
    {
        $game = Subscriber::findOrFail($game);
        return view('admin.games.edit', compact('game', 'lang'));
    }


    public function update(Request $request, $lang, $game)
    {
        // Log the incoming request data
        Log::info('SubscriberController@update: Incoming request data', $request->all());

        $subscriber = Subscriber::findOrFail($game);

        // **نحفظ قيمة activation_code الحالية قبل التحديث**
        $oldActivationCode = $subscriber->activation_code;

        // Log the old activation code
        Log::info('SubscriberController@update: Old Activation Code: ' . ($oldActivationCode ?? 'NULL'));


        $validated = $request->validate([
            'dns_username' => 'nullable|string|max:255',
            'dns_password' => 'nullable|string|max:255',
            'dns_link' => 'nullable|url|max:255',
            'dns_expiry_date' => 'nullable|date',
            'activation_code' => 'nullable|string|max:20', // أو 'required'
            'status' => 'required|in:active,inactive,expired,canceled,pending_dns',
        ]);

        // حفظ التغييرات في قاعدة البيانات
        $subscriber->update($validated); // يتم تحديث بيانات المشترك، بما في ذلك activation_code و DNS

        // **Log the new activation code after update**
        Log::info('SubscriberController@update: New Activation Code: ' . ($subscriber->activation_code ?? 'NULL'));


        // **الشرط الأصلي الصحيح لإرسال البريد مرة واحدة فقط عند التعيين لأول مرة:**
        // 1. المشترك لديه بريد إلكتروني صالح.
        // 2. قيمة activation_code كانت فارغة قبل التحديث ($oldActivationCode).
        // 3. قيمة activation_code أصبحت غير فارغة بعد التحديث ($subscriber->activation_code).

        $emailIsValid = !empty($subscriber->email) && is_string($subscriber->email);
        $codeWasEmpty = empty($oldActivationCode); // نستخدم القيمة القديمة هنا
        $codeIsNotEmptyNow = !empty($subscriber->activation_code); // **نستخدم قيمة المشترك بعد التحديث هنا**


        // Log the conditions evaluation
        Log::info('SubscriberController@update: Email Valid: ' . ($emailIsValid ? 'true' : 'false') .
            ', Code Was Empty: ' . ($codeWasEmpty ? 'true' : 'false') .
            ', Code Is Not Empty Now: ' . ($codeIsNotEmptyNow ? 'true' : 'false') .
            ', Final Condition: ' . ($emailIsValid && $codeWasEmpty && $codeIsNotEmptyNow ? 'TRUE' : 'FALSE'));


        // **الشرط الأصلي الذي يُرسل البريد:**
        if ($emailIsValid && $codeWasEmpty && $codeIsNotEmptyNow) {
            try {
                Log::info('Attempting to queue FINAL confirmation email (FIRST ACTIVATION) for subscriber: ' . $subscriber->id);

                Mail::queue(new PaymentConfirmation($subscriber, $lang), [], function ($message) use ($subscriber) {
                    $message->to($subscriber->email)->onQueue('default');
                });

                Log::info('FINAL confirmation email (FIRST ACTIVATION) queued for subscriber: ' . $subscriber->id);
            } catch (\Exception $e) {
                Log::error('Failed to queue FINAL confirmation email (FIRST ACTIVATION): ' . $e->getMessage(), [
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'error_trace' => $e->getTraceAsString()
                ]);
                $errorMessage = $lang == 'ar' ? 'فشل إرسال البريد الإلكتروني للمستخدم بعد تعيين رمز التفعيل.' : 'Failed to send email to user after setting activation code.';
                return redirect()->route('games.index', ['lang' => $lang])
                    ->with('success', $lang == 'ar' ? 'تم تحديث الإشتراك بنجاح.' : 'Subscription updated successfully.')
                    ->with('error', $errorMessage);
            }
        } else {
            // هذا الجزء سيعمل في حالات التحديثات اللاحقة أو إذا كان البريد/الكود مفقوداً أو لم يتغير من فارغ إلى غير فارغ
            Log::info('Email not sent from admin update for subscriber: ' . $subscriber->id . ' (Condition was false)');
        }


        // إعادة التوجيه برسالة نجاح بغض النظر عما إذا كان البريد قد تم إرساله في هذه المرة أم لا
        $successMessage = $lang == 'ar' ? 'تم تحديث الإشتراك بنجاح.' : 'Subscription updated successfully.';
        // يمكنك إضافة معلومات لرسالة النجاح إذا تم إرسال البريد في هذه المرة
        // الشرط هنا لا يزال يستخدم $codeWasEmpty و $codeIsNotEmptyNow كما في الشرط الرئيسي
        if ($emailIsValid && $codeWasEmpty && $codeIsNotEmptyNow) {
            $successMessage .= ($lang == 'ar' ? ' وتم إرسال بريد التفعيل للمستخدم.' : ' And activation email sent to user.');
        }

        return redirect()->route('games.index', ['lang' => $lang])
            ->with('success', $successMessage);
    }





    public function showForm(Request $request, $lang)
    {
        $mainCategoryId = $request->query('main_category_id');
        $subCategoryId = $request->query('sub_category_id');
        $duration = $request->query('duration', 30); // افتراضي 30 إذا لم يتم تمرير القيمة

        // Validate query parameters
        if (!$mainCategoryId || !$subCategoryId) {
            return redirect()->route('plans', ['lang' => $lang])
                ->with('error', $lang == 'ar' ? 'باقة أو خطة غير صالحة.' : 'Invalid package or plan.');
        }

        // جلب MainCategory مع SubCategories
        $mainCategory = MainCategory::with('subCategories')->findOrFail($mainCategoryId);

        // جلب SubCategory بناءً على sub_category_id الممرر
        $subCategory = SubCategory::where('id', $subCategoryId)
            ->where('main_category_id', $mainCategoryId)
            ->first();

        if (!$subCategory) {
            return redirect()->route('plans', ['lang' => $lang])
                ->with('error', $lang == 'ar' ? 'الخطة غير متاحة.' : 'Plan not available.');
        }

        // استخدام السعر والمدة من SubCategory المختارة
        $price = $subCategory->price;
        $duration = $subCategory->duration;

        $game = $mainCategory;

        return view('subscriber.form', compact('mainCategory', 'subCategory', 'price', 'duration', 'lang', 'game'));
    }

    public function store(Request $request)
    {
        $lang = $request->route('lang'); // جلب الـ lang من الـ route

        $validated = $request->validate([
            'game_id' => 'required|exists:main_categories,id',
            'duration' => 'required|integer',
            'country' => 'required|string|in:EG,SA,AE,KW,QA,BH,OM,JO,LB,MA,TN,DZ',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // جلب السعر بناءً على sub_category_id أو duration
        $subCategory = SubCategory::where('main_category_id', $validated['game_id'])
            ->where('duration', $validated['duration'])
            ->first();

        if (!$subCategory) {
            return redirect()->back()->with('error', $lang == 'ar' ? 'الخطة غير متاحة.' : 'Plan not available.');
        }

        // Store subscriber data
        $subscriber = Subscriber::create([
            'main_category_id' => $validated['game_id'],
            'sub_category_id' => $subCategory->id,
            'country' => $validated['country'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'price' => $subCategory->price,
            'duration' => $validated['duration'],
            'status' => 'بانتظار DNS',
        ]);

        // Redirect to confirmation page
        return redirect()->route('subscriber.confirm', [
            'lang' => $lang,
            'subscriber_id' => $subscriber->id
        ])->with('success', $lang == 'ar' ? 'تم حفظ بيانات الاشتراك بنجاح!' : 'Subscriber data saved successfully!');
    }

    public function showConfirm(Request $request, $lang, $subscriber_id)
    {
        $subscriber = Subscriber::with('mainCategory', 'subCategory')->findOrFail($subscriber_id);
        $price = $subscriber->subCategory->price ?? 0;

        return view('subscriber.confirm', compact('subscriber', 'price', 'lang'));
    }
}
