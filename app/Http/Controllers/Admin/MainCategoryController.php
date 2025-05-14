<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MainCategoryController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();
        $categories = MainCategory::all();
        return view('admin.main_categories.index', compact('categories', 'lang'));
    }

    public function create()
    {
        $lang = app()->getLocale();
        return view('admin.main_categories.create', compact('lang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
        ]);

        $data = $request->all();

        // التعامل مع رفع الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('main_categories', 'public');
        }

        MainCategory::create($data);

        return redirect()->route('admin.main_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم إضافة التصنيف بنجاح!');
    }

    public function show(MainCategory $mainCategory)
    {
        $lang = app()->getLocale();
        return view('admin.main_categories.show', compact('mainCategory', 'lang'));
    }

    public function edit($lang, MainCategory $mainCategory)
    {
        return view('admin.main_categories.edit', compact('mainCategory', 'lang'));
    }

    public function update(Request $request, $lang, $id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // التعامل مع تحديث الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($mainCategory->image) {
                Storage::disk('public')->delete($mainCategory->image);
            }
            $data['image'] = $request->file('image')->store('main_categories', 'public');
        }

        $mainCategory->update($data);

        return redirect()->route('admin.main_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم تحديث التصنيف بنجاح!');
    }

    public function destroy($lang, $id)
    {
        $mainCategory = MainCategory::findOrFail($id);

        // حذف الصورة إذا وجدت
        if ($mainCategory->image) {
            Storage::disk('public')->delete($mainCategory->image);
        }

        $mainCategory->delete();
        return redirect()->route('admin.main_categories.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم حذف التصنيف بنجاح!');
    }
}
