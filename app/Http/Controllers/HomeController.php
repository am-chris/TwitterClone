<?php

namespace App\Http\Controllers;

use Auth;
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
        $usersImFollowing = [];

        $usersImFollowing = Follow::where('follower_id', Auth::id())
            ->pluck('followed_id')
            ->toArray();

        array_push($usersImFollowing, Auth::id()); // Add current user to array

        $blockedUserIds = Block::where('blocker_id', Auth::id())
            ->pluck('blocked_id')
            ->toArray();

        $followSuggestions = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $usersImFollowing)
            ->whereNotIn('id', $blockedUserIds)
            ->get()
            ->take(3);

        return view('home', compact('followSuggestions'));
    }
}
