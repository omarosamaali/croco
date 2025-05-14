<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsAndConditions;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    // عرض الشروط والأحكام (Index)
    public function index($lang)
    {
        $terms = TermsAndConditions::first(); // افترض أن لديك سجل واحد فقط
        $data = getViewData($lang); // استخدام الدالة الموجودة لديك لضبط اللغة
        $data['terms'] = $terms;

        return view('admin.terms.index', $data);
    }

    // صفحة إنشاء/تعديل الشروط والأحكام (Create/Edit)
    public function create($lang)
    {
        $terms = TermsAndConditions::first(); // جلب السجل الأول إذا كان موجودًا
        $data = getViewData($lang);
        $data['terms'] = $terms;

        return view('admin.terms.create', $data);
    }

    // تحديث الشروط والأحكام (Update)
    public function update(Request $request, $lang)
    {
        $terms = TermsAndConditions::first();

        if (!$terms) {
            $terms = new TermsAndConditions();
        }

        $terms->content_ar = $request->input('content_ar');
        $terms->content_en = $request->input('content_en');
        $terms->save();

        return redirect()->route('terms.index', ['lang' => $lang])
                        ->with('success', $lang == 'ar' ? 'تم تحديث الشروط والأحكام بنجاح' : 'Terms and Conditions updated successfully');
    }
}