<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;

class UserPrivacyPolicyController extends Controller
{
    public function show($lang)
    {
        $policy = PrivacyPolicy::first(); // Get the first Privacy Policy
        return view('privacy-policy', compact('policy', 'lang'));
    }
}