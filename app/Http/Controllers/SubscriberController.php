<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function showForm(Request $request)
    {
        $lang = app()->getLocale();
        $gameId = $request->query('game_id');
        $duration = $request->query('duration');

        // Validate query parameters
        if (!$gameId || !$duration) {
            return redirect()->route('games.index', ['lang' => $lang])->with('error', 'Invalid game or duration.');
        }

        $game = Game::with('subCategories')->findOrFail($gameId);
        $price = $game->subCategories->where('duration', $duration)->first()->price ?? 0;

        return view('subscriber.form', compact('game', 'duration', 'price', 'lang'));
    }
    public function showConfirm(Request $request, $subscriber_id)
    {
        $lang = app()->getLocale();
        $subscriber = Subscriber::with('game.subCategories')->findOrFail($subscriber_id);
        $price = $subscriber->game->subCategories->where('duration', $subscriber->duration)->first()->price ?? 0;

        return view('subscriber.confirm', compact('subscriber', 'price', 'lang'));
    }
    public function store(Request $request)
    {
        $lang = app()->getLocale();

        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'duration' => 'required|integer',
            'country' => 'required|string|in:EG,SA,AE,KW,QA,BH,OM,JO,LB,MA,TN,DZ',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Store subscriber data
        $subscriber = Subscriber::create([
            'game_id' => $validated['game_id'],
            'duration' => $validated['duration'],
            'country' => $validated['country'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'status' => 'pending',
        ]);

        // Redirect to confirmation page
        return redirect()->route('subscriber.confirm', [
            'lang' => $lang,
            'subscriber_id' => $subscriber->id
        ])->with('success', 'Subscriber data saved successfully!');
    }
}
