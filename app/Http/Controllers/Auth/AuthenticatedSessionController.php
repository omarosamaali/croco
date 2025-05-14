<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    // التحقق من بيانات الاعتماد
    $request->authenticate();

    // إعادة توليد الجلسة لضمان الأمان
    $request->session()->regenerate();

    // توجيه المستخدم إلى المسار مع إضافة اللغة (إذا كانت موجودة)
    $lang = session('language', 'ar'); // الحصول على اللغة من الجلسة
    return redirect()->intended(route('dashboard', ['lang' => $lang], false)); // استخدام اسم المسار
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
