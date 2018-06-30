<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\Models\Post;
use App\Models\User\Block;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $mentioned_post_ids = [];
        foreach (Auth::user()->notifications as $notification) {
            array_push($mentioned_post_ids, $notification->data['post_id']);
        }

        $posts = Post::whereIn('id', $mentioned_post_ids)
            ->orderBy('created_at', 'desc')
            ->get();

        $follow_requests = FollowRequest::with('follower')
            ->where('followed_id', Auth::id())
            ->get();

        return view('notifications.index', compact('follow_requests', 'follow_suggestions', 'posts'));
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
