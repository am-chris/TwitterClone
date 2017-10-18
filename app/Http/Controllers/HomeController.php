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
        $users_im_following = [];

        $users_im_following = Follow::where('follower_id', Auth::id())
            ->pluck('followed_id')
            ->toArray();

        array_push($users_im_following, Auth::id()); // Add current user to array

        $blocked_user_ids = Block::where('blocker_id', Auth::id())
            ->pluck('blocked_id')
            ->toArray();

        $follow_suggestions = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $users_im_following)
            ->whereNotIn('id', $blocked_user_ids)
            ->get()
            ->take(3);

        return view('home', compact('follow_suggestions'));
    }
}
