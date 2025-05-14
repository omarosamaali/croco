<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     *
     * @param string $lang
     * @return \Illuminate\View\View
     */
    public function index($lang)
    {
        // يمكن هنا معالجة اللغة إذا لزم الأمر
        return view('dashboard', compact('lang'));
    }
}
