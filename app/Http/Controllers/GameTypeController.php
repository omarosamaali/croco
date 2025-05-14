<?php

namespace App\Http\Controllers;

use App\Models\GameType;
use Illuminate\Http\Request;

class GameTypeController extends Controller
{
    public function index()
    {
        $gameTypes = GameType::all();
        return view('dashboard', compact('gameTypes'));
    }

    public function create()
    {
        return view('game_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'name_en' => 'required',
        ]);
        
        GameType::create($request->all());
        
        return redirect()->route('game-types.index')
            ->with('success', 'تم إنشاء نوع اللعبة بنجاح.');
    }

    public function show(GameType $gameType)
    {
        return view('game_types.show', compact('gameType'));
    }

    public function edit(GameType $gameType)
    {
        return view('game_types.edit', compact('gameType'));
    }

    public function update(Request $request, GameType $gameType)
    {
        $request->validate([
            'name' => 'required',
            'name_en' => 'required',
        ]);
        
        $gameType->update($request->all());
        
        return redirect()->route('game-types.index')
            ->with('success', 'تم تحديث نوع اللعبة بنجاح.');
    }

    public function destroy(GameType $gameType)
    {
        $gameType->delete();
        
        return redirect()->route('game-types.index')
            ->with('success', 'تم حذف نوع اللعبة بنجاح.');
    }
}