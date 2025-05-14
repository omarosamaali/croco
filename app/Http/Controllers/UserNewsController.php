<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class UserNewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::all();
        return view('news', compact('news'));
    }

    public function show($id, $data)
    {
        $newsItem = News::findOrFail($id); // Fetch the news item by ID
        $data['newsItem'] = $newsItem; // Add the news item to the data array
        return view('news-show', $data); // Return the single news view
    }
}