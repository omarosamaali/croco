<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function show($lang, Game $game)
    {
        return view('admin.games.show', compact('game', 'lang'));
    }
    public function index(Request $request, $lang)
    {
        $query = Game::query();
        $games = $query->with(['mainCategory', 'subCategories'])->paginate(10);
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('status') && in_array($request->status, ['active', 'inactive', 'expired', 'canceled', 'pending_dns'])) {
            $query->where('status', $request->status);
        }

        $games = $query->with(['mainCategory', 'subCategories'])->paginate(10);

        return view('admin.games.index', compact('games', 'lang'));
    }

    public function create()
    {
        $lang = request()->route('lang');
        $mainCategories = MainCategory::where('status', '1')->get();
        $subCategories = SubCategory::where('status', true)->get();
        return view('admin.games.create', compact('lang', 'mainCategories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $lang = request()->route('lang');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|in:EG,SA,AE,KW,QA,BH,OM,JO,LB,MA,TN,DZ',
            'main_category' => 'required|exists:main_categories,id',
            'sub_categories' => 'required|array|min:1',
            'sub_categories.*' => 'exists:sub_categories,id',
            'description_ar' => 'required|array|min:1',
            'description_ar.*' => 'required|string|max:255',
            'description_en' => 'required|array|min:1',
            'description_en.*' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'dns_usernames.*' => 'nullable|string|max:255',
            'dns_urls.*' => 'nullable|url',
            'dns_expiry_date' => 'nullable|date',
            'activation_code' => 'required|string|size:10',
            'status' => 'required|in:active,inactive,expired,canceled,pending_dns',
        ]);

        $activation_code = $this->generateUniqueActivationCode();
        $image = $this->getImageForCategory($validated['main_category']);
        $registration_date = now()->toDateString();

        // حساب أبعد تاريخ انتهاء بناءً على الخطط
        $maxDays = 30;
        foreach ($validated['sub_categories'] as $subCategoryId) {
            $subCategory = SubCategory::find($subCategoryId);
            if ($subCategory && ($subCategory->duration ?? 30) > $maxDays) {
                $maxDays = $subCategory->duration ?? 30;
            }
        }
        $expiry_date = now()->addDays($maxDays)->toDateString();

        $dnsServers = [];
        if ($request->dns_usernames) {
            foreach ($request->dns_usernames as $index => $username) {
                if ($username || $request->dns_urls[$index]) {
                    $dnsServers[] = [
                        'username' => $username,
                        'url' => $request->dns_urls[$index] ?? null,
                    ];
                }
            }
        }

        $game = Game::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'main_category' => $validated['main_category'],
            'image' => $image,
            'description_ar' => $validated['description_ar'],
            'description_en' => $validated['description_en'],
            'registration_date' => $registration_date,
            'expiry_date' => $expiry_date,
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'dns_servers' => json_encode($dnsServers),
            'dns_expiry_date' => $validated['dns_expiry_date'],
            'activation_code' => $activation_code,
            'status' => $validated['status'],
        ]);

        $game->subCategories()->attach($validated['sub_categories']);

        return redirect()->route('games.index', ['lang' => $lang])->with('success', 'تم إضافة الحساب بنجاح!');
    }

    public function edit($lang, Game $game)
    {
        $mainCategories = MainCategory::where('status', '1')->get();
        $subCategories = SubCategory::where('status', true)->get();
        $selectedSubCategories = $game->subCategories->pluck('id')->toArray();
        return view('admin.games.edit', compact('game', 'lang', 'mainCategories', 'subCategories', 'selectedSubCategories'));
    }

    public function update(Request $request, $lang, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|in:EG,SA,AE,KW,QA,BH,OM,JO,LB,MA,TN,DZ',
            'main_category' => 'required|exists:main_categories,id',
            'sub_categories' => 'required|array|min:1',
            'sub_categories.*' => 'exists:sub_categories,id',
            'description_ar' => 'required|array|min:1',
            'description_ar.*' => 'required|string|max:255',
            'description_en' => 'required|array|min:1',
            'description_en.*' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'dns_usernames.*' => 'nullable|string|max:255',
            'dns_urls.*' => 'nullable|url',
            'dns_expiry_date' => 'nullable|date',
            'activation_code' => 'required|string|size:10',
            'status' => 'required|in:active,inactive,expired,canceled,pending_dns',
        ]);

        $activation_code = $validated['activation_code'];
        if ($activation_code != $game->activation_code) {
            if (Game::where('activation_code', $activation_code)->where('id', '!=', $game->id)->exists()) {
                return redirect()->back()->withErrors(['activation_code' => 'رمز التفعيل مستخدم بالفعل'])->withInput();
            }
        }

        $image = $this->getImageForCategory($validated['main_category']);

        $maxDays = 30;
        foreach ($validated['sub_categories'] as $subCategoryId) {
            $subCategory = SubCategory::find($subCategoryId);
            if ($subCategory && ($subCategory->duration ?? 30) > $maxDays) {
                $maxDays = $subCategory->duration ?? 30;
            }
        }
        $expiry_date = (new \DateTime($game->registration_date))->modify("+{$maxDays} days")->format('Y-m-d');

        $dnsServers = [];
        if ($request->existing_dns) {
            foreach ($request->existing_dns as $index => $dns) {
                $dnsServers[] = [
                    'username' => $request->updated_dns[$index]['username'] ?? $dns['username'],
                    'url' => $request->updated_dns[$index]['url'] ?? $dns['url'],
                ];
            }
        }
        if ($request->dns_usernames) {
            foreach ($request->dns_usernames as $index => $username) {
                if ($username || $request->dns_urls[$index]) {
                    $dnsServers[] = [
                        'username' => $username,
                        'url' => $request->dns_urls[$index] ?? null,
                    ];
                }
            }
        }

        $game->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'main_category' => $validated['main_category'],
            'image' => $image,
            'description_ar' => $validated['description_ar'],
            'description_en' => $validated['description_en'],
            'registration_date' => $game->registration_date,
            'expiry_date' => $expiry_date,
            'username' => $validated['username'],
            'password' => $request->password ? bcrypt($validated['password']) : $game->password,
            'dns_servers' => json_encode($dnsServers),
            'dns_expiry_date' => $validated['dns_expiry_date'],
            'activation_code' => $activation_code,
            'status' => $validated['status'],
        ]);

        $game->subCategories()->sync($validated['sub_categories']);

        return redirect()->route('games.index', ['lang' => $lang])->with('success', 'تم تحديث الحساب بنجاح!');
    }

    public function destroy($lang, $id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->route('games.index', ['lang' => $lang])
            ->with('success', 'تم حذف الحساب بنجاح!');
    }

    private function getImageForCategory($category_id)
    {
        $images = [
            1 => 'action_image.jpg',
            2 => 'adventure_image.jpg',
            3 => 'sports_image.jpg',
        ];
        return $images[$category_id] ?? 'verified_image.jpg';
    }

    private function generateUniqueActivationCode()
    {
        $attempts = 0;
        $maxAttempts = 10;

        do {
            $code = '';
            $usedDigits = [];

            for ($i = 0; $i < 10; $i++) {
                do {
                    $digit = mt_rand(0, 9);
                } while (in_array($digit, $usedDigits));

                $usedDigits[] = $digit;
                $code .= $digit;
            }

            $exists = Game::where('activation_code', $code)->exists();

            if (!$exists) {
                return $code;
            }

            $attempts++;
        } while ($attempts < $maxAttempts);

        return time() . mt_rand(1000, 9999);
    }
}
