<?php

namespace App\Http\Controllers;

use Auth;
use Redis;
use App\User;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User\Block;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersImFollowing = Redis::zrange('following:' . Auth::id(), 0, -1);
        $usersImBlocking = Redis::zrange('blocking:' . Auth::id(), 0, -1);

        $followSuggestions = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $usersImFollowing)
            ->whereNotIn('id', $usersImBlocking)
            ->get()
            ->take(3);

        $trendingHashtags = array_map('json_decode', Redis::zrevrange('trending_hashtags', 0, 4, 'WITHSCORES'));

        return view('home', compact('followSuggestions', 'trendingHashtags'));
    }
}
