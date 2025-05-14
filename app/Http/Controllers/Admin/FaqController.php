<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();
        $faqs = Faq::orderBy('order')->paginate(10);
        return view('admin.faqs.index', compact('faqs', 'lang'));
    }

    public function create()
    {
        $lang = app()->getLocale();
        return view('admin.faqs.create', compact('lang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_ar' => 'required|string|max:255',
            'question_en' => 'required|string|max:255',
            'answer_ar' => 'required|string',
            'answer_en' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Faq::create($validated);
        
        $lang = app()->getLocale();
        return redirect()->route('faqs.index', ['lang' => $lang])->with('success', 'تمت إضافة السؤال بنجاح!');
    }

    // public function edit(Faq $faq)
    // {
    //     $lang = app()->getLocale();
    //     return view('admin.faqs.edit', compact('faq', 'lang'));
    // }
    public function edit($lang, Faq $faq)
{
    return view('admin.faqs.edit', compact('faq', 'lang'));
}

public function update(Request $request, $lang, Faq $faq)
    {
        $validated = $request->validate([
            'question_ar' => 'required|string|max:255',
            'question_en' => 'required|string|max:255',
            'answer_ar' => 'required|string',
            'answer_en' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable', // Checkbox sends '1' or nothing
        ]);

        // Prepare data for update, ensuring is_active is a boolean
        $faqData = [
            'question_ar' => $validated['question_ar'],
            'question_en' => $validated['question_en'],
            'answer_ar' => $validated['answer_ar'],
            'answer_en' => $validated['answer_en'],
            'order' => $validated['order'] ?? 0, // Default to 0 if null
            'is_active' => $request->has('is_active') ? 1 : 0, // Convert checkbox to boolean
        ];

        $faq->update($faqData);

        return redirect()->route('faqs.index', ['lang' => $lang])
            ->with('success', $lang == 'ar' ? 'تم تحديث السؤال بنجاح!' : 'FAQ updated successfully!');
    }
    public function destroy($lang, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faqs.index', ['lang' => $lang])->with('success', 'تم حذف السؤال بنجاح!');
    }
}