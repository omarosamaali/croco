<?php
namespace App\Http\Controllers;

use App\Models\TermsAndConditions; // تأكد من وجود موديل الشروط والأحكام
use App\Models\Banner;
use App\Models\Game;
use App\Models\News;
use Illuminate\Support\Facades\App;

class UserTermsController extends Controller
{
    public function index($lang)
    {
        App::setLocale($lang);
        session(['language' => $lang]);

        // استرجاع الشروط والأحكام من قاعدة البيانات
        $terms = TermsAndConditions::first(); // أو استخدم الطريقة المناسبة للحصول على الشروط

        return view('terms', [
            'lang' => $lang,
            'terms' => $terms,
            'games' => Game::all(),
            'news' => News::all(),
            'banners' => Banner::where('is_active', 1)
                ->where('expiration_date', '>=', now())
                ->get()
        ]);
    }
}
