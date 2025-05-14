<?php

// app/Http/Controllers/Admin/NewsController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $lang = app()->getLocale();
        return view('admin.news.create', compact('lang'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'comments_count' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['image', 'secondary_image']);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
            $data['image_path'] = $imagePath;
        }

        // Handle secondary image upload
        if ($request->hasFile('secondary_image')) {
            $secondaryImagePath = $request->file('secondary_image')->store('news', 'public');
            $data['secondary_image'] = $secondaryImagePath;
        }

        // Create new news item
        News::create($data);

        return redirect()->route('news.index', ['lang' => app()->getLocale()])
            ->with('success', 'تم إضافة الخبر بنجاح');
    }



    public function edit($lang, News $news)
    {
        return view('admin.news.edit', compact('news', 'lang'));
    }
    public function update(Request $request, $lang, News $news)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'comments_count' => 'nullable|integer|min:0',
        ]);

        // تجهيز البيانات الأساسية
        $data = $request->only([
            'title_ar',
            'title_en',
            'description_ar',
            'description_en',
            'author',
            'comments_count'
        ]);

        // التعامل مع الصورة الجديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }

            // تخزين الصورة الجديدة
            $data['image_path'] = $request->file('image')->store('news', 'public');
        }

        // التعامل مع الصورة الخطط
        if ($request->hasFile('secondary_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($news->secondary_image) {
                Storage::disk('public')->delete($news->secondary_image);
            }

            // تخزين الصورة الجديدة
            $data['secondary_image'] = $request->file('secondary_image')->store('news', 'public');
        }


        // تحديث الخبر
        $news->update($data);

        // التعامل مع المنصات
        $platformsData = [];
        if ($request->existing_platforms) {
            foreach ($request->existing_platforms as $index => $platform) {
                $imagePath = $platform['image_path'];
                if (isset($request->remove_platform_image[$index])) {
                    Storage::disk('public')->delete($imagePath);
                    $imagePath = null;
                }
                if (isset($request->updated_platform_images[$index])) {
                    $imagePath = $request->updated_platform_images[$index]->store('platforms', 'public');
                }
                $platformsData[] = [
                    'name' => $request->updated_platforms[$index]['name'],
                    'url' => $request->updated_platforms[$index]['url'],
                    'image_path' => $imagePath,
                ];
            }
        }

        if ($request->platforms) {
            foreach ($request->platforms as $index => $name) {
                $platformsData[] = [
                    'name' => $name,
                    'url' => $request->platform_urls[$index] ?? null,
                    'image_path' => isset($request->platform_images[$index])
                        ? $request->platform_images[$index]->store('platforms', 'public')
                        : null,
                ];
            }
        }

        // بإمكانك تحديث علاقة المنصات هنا مثلاً:
        // $news->platforms()->delete();
        // $news->platforms()->createMany($platformsData);

        return redirect()->route('news.index', ['lang' => $lang])->with('success', 'تم تحديث الخبر بنجاح!');
    }



    public function destroy($lang, $id)
    {
        $news = news::findOrFail($id);

        // حذف الصورة إن وجدت
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        // إعادة التوجيه إلى قائمة الألعاب بعد الحذف بنجاح
        return redirect()->route('news.index', ['lang' => $lang])
            ->with('success', 'تم حذف الخبر بنجاح!');
    }
}
