<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentTransaction;
use App\Models\SubCategory;
use App\Models\MainCategory;

class SubscriberController extends Controller
{
    public function edit($lang, $game)
    {
        $game = Subscriber::findOrFail($game);
        return view('games.edit', compact('game', 'lang'));
    }

    public function update(Request $request, $lang, $game)
    {
        $subscriber = Subscriber::findOrFail($game);

        $validated = $request->validate([
            'dns_username' => 'nullable|string|max:255',
            'dns_password' => 'nullable|string|max:255',
            'dns_link' => 'nullable|url|max:255',
            'dns_expiry_date' => 'nullable|date',
            'activation_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,expired,canceled,pending_dns',
        ]);

        $subscriber->update($validated);

        return redirect()->route('games.index', ['lang' => $lang])
            ->with('success', $lang == 'ar' ? 'تم تحديث الإشتراك بنجاح!' : 'Subscription updated successfully!');
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
