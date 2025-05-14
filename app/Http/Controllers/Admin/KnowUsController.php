<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KnowUsController extends Controller
{
    public function index()
    {
        $lang = request()->route('lang');
        $knowUsItems = KnowUs::latest()->paginate(10);
        return view('admin.know-us.index', compact('knowUsItems', 'lang'));
    }

    public function create()
    {
        $lang = request()->route('lang');
        return view('admin.know-us.create', compact('lang'));
    }

    public function store(Request $request)
    {
        $lang = request()->route('lang');
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'comments_count' => 'required|integer|min:0',
        ]);
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('know-us', 'public');
        }
        KnowUs::create($validated);
        return redirect()->route('know-us.index', ['lang' => $lang])->with('success', 'تم إضافة المحتوى بنجاح!');
    }

public function edit($lang, $id)
{
    $knowUs = KnowUs::find($id);

    if (!$knowUs) {
        return redirect()->route('know-us.index', ['lang' => $lang])
            ->withErrors('Record not found.');
    }

    return view('admin.know-us.edit', compact('knowUs', 'lang'));
}

public function update(Request $request, $lang, $id)
{
    $knowUs = KnowUs::findOrFail($id);

    $validated = $request->validate([
        'title_ar' => 'required|string|max:255',
        'title_en' => 'required|string|max:255',
        'description_ar' => 'required|string',
        'description_en' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'author' => 'required|string|max:255',
        'comments_count' => 'required|integer|min:0',
    ]);

    if ($request->hasFile('image')) {
        if ($knowUs->image_path) {
            Storage::disk('public')->delete($knowUs->image_path);
        }
        $validated['image_path'] = $request->file('image')->store('know-us', 'public');
    }

    $knowUs->update($validated);

    return redirect()->route('know-us.index', ['lang' => $lang])->with('success', 'تم تعديل المحتوى بنجاح!');
}


    public function destroy($lang, $id)
    {
        $knowUs = KnowUs::findOrFail($id);
        if ($knowUs->image_path) {
            Storage::disk('public')->delete($knowUs->image_path);
        }
        $knowUs->delete();
        return redirect()->route('know-us.index', ['lang' => $lang])
            ->with('success', 'تم حذف المحتوى بنجاح!');
    }
}