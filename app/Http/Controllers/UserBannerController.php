<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Game;
use App\Models\News;
use Illuminate\Support\Facades\App;

class UserBannerController extends Controller
{
    public function index($lang)
    {
        App::setLocale($lang);
        session(['language' => $lang]);
        
        return view('welcome', [
            'lang' => $lang,
            'games' => Game::all(),
            'news' => News::all(),
            'banners' => Banner::where('is_active', 1)
                ->where('expiration_date', '>=', now())
                ->get()
        ]);
    }
}