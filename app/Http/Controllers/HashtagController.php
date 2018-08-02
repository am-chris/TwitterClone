<?php

namespace App\Http\Controllers;

use Auth;
use Redis;
use App\Models\Follow;
use App\Models\User\Block;
use App\User;
use App\Models\Post;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($query)
    {
        $posts = Post::where('content', 'like', '%' . '#' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();

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

        $trendingHashtags = array_map('json_decode', Redis::zrevrange('trending_hashtags', 0, 4, 'WITHSCORES'));
        
        return view('hashtags.index', compact('posts', 'followSuggestions', 'trendingHashtags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
