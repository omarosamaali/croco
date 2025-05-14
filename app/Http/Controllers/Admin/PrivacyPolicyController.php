<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index($lang)
    {
        $policy = PrivacyPolicy::first(); // Get the first Privacy Policy (assuming one record)
        return view('admin.privacy-policy.index', compact('policy', 'lang'));
    }

    public function create($lang)
    {
        return view('admin.privacy-policy.create', compact('lang'));
    }

public function store(Request $request, $lang)
{
    $request->validate([
        'content_ar' => 'required|string',
        'content_en' => 'required|string',
    ]);

    PrivacyPolicy::create([
        'content_ar' => $request->content_ar,
        'content_en' => $request->content_en,
    ]);

    return redirect()->route('privacy-policy.index', ['lang' => $lang])
        ->with('success', 'تم إنشاء سياسة الخصوصية بنجاح');
}
    public function edit($lang, PrivacyPolicy $privacyPolicy)
    {
        return view('admin.privacy-policy.edit', compact('privacyPolicy', 'lang'));
    }

    public function update(Request $request, $lang, PrivacyPolicy $privacyPolicy)
    {
        $request->validate([
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
        ]);

        $privacyPolicy->update([
            'content_ar' => $request->content_ar,
            'content_en' => $request->content_en,
        ]);

        return redirect()->route('privacy-policy.index', ['lang' => $lang])
            ->with('success', 'تم تحديث سياسة الخصوصية بنجاح');
    }
}