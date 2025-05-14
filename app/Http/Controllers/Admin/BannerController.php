<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BannerController extends Controller
{
    public function index($lang)
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();
        return view('admin.banners.index', compact('banners', 'lang'));
    }

    public function create($lang)
    {
        return view('admin.banners.create', compact('lang'));
    }

    public function store(Request $request, $lang)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:2048', // Max 2MB
            'start_date' => 'required|date',
            'expiration_date' => 'required|date|after:start_date',
            'is_active' => 'nullable',
            'location' => 'required|string',
        ]);

        // التحقق من عدم وجود بانر آخر بنفس التوقيت
        $startDate = $validated['start_date'];
        $expirationDate = $validated['expiration_date'];
        $location = $validated['location'];

        // البحث عن أي بانر يتداخل مع التاريخ المحدد في نفس الموقع
        $overlappingBanner = Banner::where('location', $location)
            ->where(function ($query) use ($startDate, $expirationDate) {
                // الشرط الجديد: يتحقق فقط من البانرات التي تتداخل بالفعل
                // تداخل: بداية البانر الجديد تقع بين بداية ونهاية بانر قديم
                // أو نهاية البانر الجديد تقع بين بداية ونهاية بانر قديم
                // أو بداية البانر القديم تقع بين بداية ونهاية البانر الجديد
                $query->where(function ($q) use ($startDate, $expirationDate) {
                    // الحالة 1: بداية البانر الجديد تقع أثناء فترة بانر قائم
                    $q->where('start_date', '<=', $startDate)
                        ->where('expiration_date', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $expirationDate) {
                    // الحالة 2: نهاية البانر الجديد تقع أثناء فترة بانر قائم
                    $q->where('start_date', '<', $expirationDate)
                        ->where('expiration_date', '>=', $expirationDate);
                })->orWhere(function ($q) use ($startDate, $expirationDate) {
                    // الحالة 3: البانر الجديد يغطي بالكامل فترة بانر قائم
                    $q->where('start_date', '>=', $startDate)
                        ->where('expiration_date', '<=', $expirationDate);
                });
            })
            ->first();

        if ($overlappingBanner) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'overlap' => $lang == 'ar'
                        ? 'لا يمكن إضافة البانر. يوجد بانر آخر في نفس الفترة الزمنية ونفس الموقع.'
                        : 'Cannot add banner. Another banner exists in the same time period and location.'
                ]);
        }

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image_path' => $imagePath,
            'start_date' => $validated['start_date'],
            'expiration_date' => $validated['expiration_date'],
            'is_active' => $request->has('is_active') ? 1 : 0,
            'location' => $validated['location'],
        ]);

        return redirect()->route('banners.index', ['lang' => $lang])
            ->with('success', $lang == 'ar' ? 'تم إضافة البانر بنجاح!' : 'Banner added successfully!');
    }

    public function edit($lang, Banner $banner)
    {
        return view('admin.banners.edit', compact('banner', 'lang'));
    }

    public function update(Request $request, $lang, Banner $banner)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'expiration_date' => 'required|date|after:start_date',
            'is_active' => 'nullable',
            'location' => 'required|string',
        ]);

        // التحقق من عدم وجود بانر آخر بنفس التوقيت (باستثناء البانر الحالي)
        $startDate = $validated['start_date'];
        $expirationDate = $validated['expiration_date'];
        $location = $validated['location'];

        // البحث عن أي بانر يتداخل مع التاريخ المحدد في نفس الموقع (باستثناء البانر الحالي)
        $overlappingBanner = Banner::where('id', '!=', $banner->id)
            ->where('location', $location)
            ->where(function ($query) use ($startDate, $expirationDate) {
                $query->where(function ($q) use ($startDate, $expirationDate) {
                    // تداخل: تاريخ البداية الجديد يقع بين تاريخ بداية وانتهاء بانر موجود
                    $q->where('start_date', '<=', $startDate)
                        ->where('expiration_date', '>=', $startDate);
                })->orWhere(function ($q) use ($startDate, $expirationDate) {
                    // تداخل: تاريخ الانتهاء الجديد يقع بين تاريخ بداية وانتهاء بانر موجود
                    $q->where('start_date', '<=', $expirationDate)
                        ->where('expiration_date', '>=', $expirationDate);
                })->orWhere(function ($q) use ($startDate, $expirationDate) {
                    // تداخل: البانر الجديد يشمل بانر موجود بالكامل
                    $q->where('start_date', '>=', $startDate)
                        ->where('expiration_date', '<=', $expirationDate);
                });
            })
            ->first();

        if ($overlappingBanner) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'overlap' => $lang == 'ar'
                        ? 'لا يمكن تحديث البانر. يوجد بانر آخر في نفس الفترة الزمنية ونفس الموقع.'
                        : 'Cannot update banner. Another banner exists in the same time period and location.'
                ]);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'image_path' => $banner->image_path,
            'start_date' => $validated['start_date'],
            'expiration_date' => $validated['expiration_date'],
            'is_active' => $request->has('is_active') ? 1 : 0,
            'location' => $validated['location'],
        ]);

        return redirect()->route('banners.index', ['lang' => $lang])
            ->with('success', $lang == 'ar' ? 'تم تحديث البانر بنجاح!' : 'Banner updated successfully!');
    }

    public function destroy($lang, Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('banners.index', ['lang' => $lang])
            ->with('success', $lang == 'ar' ? 'تم حذف البانر بنجاح!' : 'Banner deleted successfully!');
    }
}
