<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
{
    public function index($lang)
    {
        App::setLocale($lang);
        $plans = Plan::orderBy('order')->get();
        return view('admin.plans.index', compact('plans', 'lang'));
    }

    public function create($lang)
    {
        App::setLocale($lang);
        // جلب جميع الخطط النشطة
        $subCategories = SubCategory::where('status', true)->get();
        return view('admin.plans.create', compact('lang', 'subCategories'));
    }

    public function store(Request $request, $lang)
    {
        App::setLocale($lang);

        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'price' => 'required|string|max:50',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_ar' => 'required|array|min:1|max:7',
            'description_en' => 'required|array|min:1|max:7',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0',
            'sub_categories' => 'required|array|min:1', // التحقق من وجود تصنيفات فرعية
            'sub_categories.*' => 'exists:sub_categories,id', // التحقق من وجود التصنيفات في قاعدة البيانات
        ]);

        // رفع الصورة وتخزين مسارها
        $imagePath = $request->file('image')->store('plans', 'public');

        $plan = Plan::create([
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'price' => $request->price,
            'image' => $imagePath,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'is_active' => $request->is_active ?? false,
            'order' => $request->order,
        ]);

        // ربط الخطط بالاشتراك
        $plan->subCategories()->attach($request->sub_categories);

        return redirect()->route('admin.plans.index', $lang)->with('success', $lang == 'ar' ? 'تم إضافة الخطة بنجاح' : 'Plan added successfully');
    }

    public function edit($lang, Plan $plan)
    {
        App::setLocale($lang);
        $subCategories = SubCategory::where('status', true)->get();
        // جلب الخطط المرتبطة بالاشتراك
        $selectedSubCategories = $plan->subCategories->pluck('id')->toArray();
        return view('admin.plans.edit', compact('plan', 'lang', 'subCategories', 'selectedSubCategories'));
    }

    public function update(Request $request, $lang, Plan $plan)
    {
        App::setLocale($lang);

        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'price' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_ar' => 'required|array|min:1|max:7',
            'description_en' => 'required|array|min:1|max:7',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0',
            'sub_categories' => 'required|array|min:1',
            'sub_categories.*' => 'exists:sub_categories,id',
        ]);

        $data = [
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'price' => $request->price,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'is_active' => $request->is_active ?? false,
            'order' => $request->order,
        ];

        // إذا تم رفع صورة جديدة، احذف الصورة القديمة وقم بتخزين الصورة الجديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($plan->image && Storage::disk('public')->exists($plan->image)) {
                Storage::disk('public')->delete($plan->image);
            }
            // رفع الصورة الجديدة
            $data['image'] = $request->file('image')->store('plans', 'public');
        }

        $plan->update($data);

        // تحديث العلاقة مع الخطط (حذف القديمة وإضافة الجديدة)
        $plan->subCategories()->sync($request->sub_categories);

        return redirect()->route('admin.plans.index', $lang)->with('success', $lang == 'ar' ? 'تم تحديث الخطة بنجاح' : 'Plan updated successfully');
    }

    public function destroy($lang, Plan $plan)
    {
        App::setLocale($lang);

        // حذف الصورة عند حذف الخطة
        if ($plan->image && Storage::disk('public')->exists($plan->image)) {
            Storage::disk('public')->delete($plan->image);
        }

        // سيتم حذف العلاقات تلقائياً بسبب onDelete('cascade') في جدول العلاقة
        $plan->delete();
        return redirect()->route('admin.plans.index', $lang)->with('success', $lang == 'ar' ? 'تم حذف الخطة بنجاح' : 'Plan deleted successfully');
    }
}
