<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameTypeController;
use App\Models\GameType;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\KnowUsController;
use App\Models\Game;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\UserNewsController;
use App\Http\Controllers\UserTermsController;
use App\Http\Controllers\UserPrivacyPolicyController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\UserBannerController;
use App\Models\Banner;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Api\GameApiController;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\PaymentController;

Route::prefix('{lang}')->where(['lang' => 'en|ar'])->group(function () {
    Route::get('plans', function ($lang) {
        App::setLocale($lang);
        $data = getViewData($lang);
        $mainCategories = \App\Models\MainCategory::with(['subCategories' => function ($query) {
            $query->select('sub_categories.id', 'sub_categories.main_category_id', 'sub_categories.name_ar', 'sub_categories.name_en', 'sub_categories.duration', 'sub_categories.price', 'sub_categories.status')
                ->where('status', 1);
        }])
            ->where('status', 1)
            ->get();
        return view('plans', array_merge($data, compact('mainCategories', 'lang')));
    })->name('plans');

    Route::get('/subscriber-form', [SubscriberController::class, 'showForm'])->name('subscriber.form');
    Route::post('/subscriber/store', [SubscriberController::class, 'store'])->name('subscriber.store');
    Route::get('/subscriber/confirm/{subscriber_id}', [SubscriberController::class, 'showConfirm'])->name('subscriber.confirm');

    Route::get('/payment/paypal/{subscriber_id}/{type?}', [PaymentController::class, 'paypal'])->name('payment.paypal');
    Route::post('/payment/process-paypal/{subscriber_id}', [PaymentController::class, 'paypal'])->name('payment.process-paypal');
    Route::post('/payment/process-card/{subscriber_id}', [PaymentController::class, 'processCardPayment'])->name('payment.process-card');
    Route::get('/payment/success/{subscriber_id}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{subscriber_id}', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/payment/transfer/{subscriber_id}', [PaymentController::class, 'showTransferForm'])->name('payment.transfer');
    Route::post('/payment/transfer/{subscriber_id}', [PaymentController::class, 'storeTransfer'])->name('payment.transfer.store');
});

// Add the missing PayPal route
Route::post('/payment/process-paypal/{subscriber_id}', [PaymentController::class, 'paypal'])->name('payment.paypal');
Route::get('/subscriber-form', [SubscriberController::class, 'showForm'])->name('subscriber.form');
Route::post('/subscriber/store', [SubscriberController::class, 'store'])->name('subscriber.store');
Route::get('/subscriber/confirm/{subscriber_id}', [SubscriberController::class, 'showConfirm'])->name('subscriber.confirm');

Route::get('/plans', function () {
    $lang = session('language', 'ar');
    return redirect("/{$lang}/plans");
});


Route::group(['prefix' => 'subscriptions', 'middleware' => ['api']], function () {
    Route::post('/check-activation', [GameApiController::class, 'checkActivationCode']);
    Route::post('/update-status', [GameApiController::class, 'updateSubscriptionStatus']);
    Route::get('/info', [GameApiController::class, 'getSubscriptionInfo']);
});


$banners = Banner::available()->orderBy('created_at', 'desc')->get();
view()->share('banners', $banners);

// Function to set locale and get view data
function getViewData($lang)
{
    App::setLocale($lang);
    session(['language' => $lang]);
    return ['lang' => $lang];
}

// Root redirect
Route::get('/', function () {
    $lang = session('language', 'ar'); // Default to 'ar' if no session
    return redirect("/{$lang}");
});

Route::prefix('{lang}')->where(['lang' => 'en|ar'])->group(function () {
    Route::get('/dashboard', function ($lang) {
        $data = getViewData($lang);
        $data['games'] = Game::all();
        return view('dashboard', $data);
    })->middleware(['auth', 'verified'])->name('dashboard');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('main_categories', MainCategoryController::class);
        Route::resource('sub_categories', SubCategoryController::class);
    });
});

Route::prefix('{lang}')->where(['lang' => 'en|ar'])->group(function () {
    Route::get('/', function ($lang) {
        $data = getViewData($lang);
        $data['games'] = Game::all();
        $data['banners'] = Banner::available()->orderBy('created_at', 'desc')->get();
        $data['news'] = \App\Models\News::all();
        return view('welcome', $data);
    })->name('home');
    Route::resource('games', GameController::class);

    Route::middleware('auth')->group(function () {
        Route::get('/profile', function ($lang) {
            $data = getViewData($lang);
            return app(ProfileController::class)->edit(request())->with($data);
        })->name('profile.edit');
        Route::patch('/profile', function ($lang) {
            return app(ProfileController::class)->update(request());
        })->name('profile.update');
        Route::delete('/profile', function ($lang) {
            return app(ProfileController::class)->destroy(request());
        })->name('profile.destroy');
    });

    // Public pages
    Route::get('/news', function ($lang) {
        $data = getViewData($lang);
        return app(UserNewsController::class)->index(request())->with($data);
    })->name('news');

    Route::get('/news/{id}', function ($lang, $id) {
        $data = getViewData($lang);
        return app(UserNewsController::class)->show($id, $data);
    })->name('news.show');

    Route::get('/know-us', function ($lang) {
        $data = getViewData($lang);
        $data['knowUs'] = \App\Models\KnowUs::first();
        return view('know-us', $data);
    })->name('know-us');

    Route::get('/contact-us', [ContactController::class, 'index'])->name('contact-us');
    Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');

    Route::get('/messages', function ($lang) {
        $data = getViewData($lang);
        return view('messages', $data);
    })->name('messages');

    Route::get('/faq', function ($lang) {
        $data = getViewData($lang);
        $data['faqs'] = \App\Models\Faq::where('is_active', true)
            ->orderBy('order')
            ->get();
        return view('faq', $data);
    })->name('faq');

    Route::get('/customer-services', function ($lang) {
        $data = getViewData($lang);
        return view('customer-services', $data);
    })->name('customer-services');

    Route::get('/comingsoon', function ($lang) {
        $data = getViewData($lang);
        return view('comingsoon', $data);
    })->name('comingsoon');

    // Admin routes
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/contact-dashboard', [ContactController::class, 'dashboard'])->name('contact.dashboard');
        Route::get('/contact/{id}', [ContactController::class, 'show'])->name('contact.show');
        Route::post('/contact/{id}/read', [ContactController::class, 'markAsRead'])->name('contact.read');
        Route::delete('/contact/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
        Route::post('/contact/{id}/replied', [ContactController::class, 'markAsReplied'])->name('contact.replied');
        Route::resource('plans', PlanController::class);
        Route::resource('news', NewsController::class);
        Route::resource('know-us', KnowUsController::class);
        Route::resource('game-types', GameTypeController::class);
        Route::resource('faqs', FaqController::class);

        // Terms and Conditions
        Route::get('terms', [TermsController::class, 'index'])->name('terms.index');
        Route::get('terms/create', [TermsController::class, 'create'])->name('terms.create');
        Route::post('terms', [TermsController::class, 'update'])->name('terms.update');

        // Privacy Policy
        Route::get('privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
        Route::get('privacy-policy/create', [PrivacyPolicyController::class, 'create'])->name('privacy-policy.create');
        Route::post('privacy-policy', [PrivacyPolicyController::class, 'store'])->name('privacy-policy.store');
        Route::get('privacy-policy/{privacyPolicy}/edit', [PrivacyPolicyController::class, 'edit'])->name('privacy-policy.edit');
        Route::put('privacy-policy/{privacyPolicy}', [PrivacyPolicyController::class, 'update'])->name('privacy-policy.update');

        // Banners
        Route::resource('banners', BannerController::class);
    });

    // User routes
    Route::get('/terms-and-conditions', [UserTermsController::class, 'index'])->name('terms');
    Route::get('/privacy-policy', [UserPrivacyPolicyController::class, 'show'])->name('privacy-policy');
});

require __DIR__ . '/auth.php';
