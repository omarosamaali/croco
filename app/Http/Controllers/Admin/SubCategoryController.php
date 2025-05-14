<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();
        $subCategories = SubCategory::with('mainCategory')->get();
        return view('admin.sub_categories.index', compact('subCategories', 'lang'));
    }

    public function create()
    {
        $lang = app()->getLocale();
        $mainCategories = MainCategory::where('status', true)->get();
        return view('admin.sub_categories.create', compact('mainCategories', 'lang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:30,90,180,365', // التأكد من القيم
            'status' => 'required|boolean',
        ]);

        // تحقق إضافي للتأكد من القيمة
        $allowedDurations = [30, 90, 180, 365];
        if (!in_array($validated['duration'], $allowedDurations)) {
            return redirect()->back()->withErrors(['duration' => 'المدة غير صالحة'])->withInput();
        }

        SubCategory::create($validated);

        return redirect()->route('admin.sub_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم إضافة التصنيف الفرعي بنجاح!');
    }

    public function show($lang, SubCategory $subCategory)
    {
        $subCategory->load('mainCategory');
        return view('admin.sub_categories.show', compact('subCategory', 'lang'));
    }

    public function edit($lang, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $mainCategories = MainCategory::where('status', true)->get();
        $subCategory->load('mainCategory');
        return view('admin.sub_categories.edit', compact('subCategory', 'mainCategories', 'lang'));
    }

    public function update(Request $request, $lang, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:30,90,180,365',
            'status' => 'required|boolean',
        ]);

        $subCategory->update($request->all());

        return redirect()->route('admin.sub_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم تحديث التصنيف الفرعي بنجاح!');
    }

    public function destroy($lang, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()->route('admin.sub_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم حذف التصنيف الفرعي بنجاح!');
    }
}
