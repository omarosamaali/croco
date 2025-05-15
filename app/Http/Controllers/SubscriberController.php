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

    public function store(Request $request, $lang)
    {
        $validated = $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'country' => 'required|string|in:EG,SA,AE,KW,QA,BH,OM,JO,LB,MA,TN,DZ',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Store subscriber data
        $subscriber = Subscriber::create([
            'main_category_id' => $validated['main_category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'country' => $validated['country'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'price' => SubCategory::find($validated['sub_category_id'])->price,
            'status' => 'pending',
        ]);

        // Redirect to confirmation page
        return redirect()->route('subscriber.confirm', [
            'lang' => $lang,
            'subscriber_id' => $subscriber->id
        ])->with('success', $lang == 'ar' ? 'تم حفظ بيانات الاشتراك بنجاح!' : 'Subscriber data saved successfully!');
    }
    public function showConfirm(Request $request, $subscriber_id)
    {
        $lang = app()->getLocale();
        $subscriber = Subscriber::with('game.subCategories')->findOrFail($subscriber_id);
        $price = $subscriber->game->subCategories->where('duration', $subscriber->duration)->first()->price ?? 0;

        return view('subscriber.confirm', compact('subscriber', 'price', 'lang'));
    }

}
