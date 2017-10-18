<?php

namespace App\Http\Controllers\Api;

use Auth;
use Response;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $users_im_following = [];

        $users_im_following = Follow::where('follower_id', $user_id)
            ->pluck('followed_id')
            ->toArray();

        array_push($users_im_following, $user_id); // Add current user to array

        $blocked_user_ids = Block::where('blocker_id', $user_id)
            ->pluck('blocked_id')
            ->toArray();

        $posts = Post::with('user')
            ->whereIn('user_id', $users_im_following)
            ->whereNotIn('user_id', $blocked_user_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Response::json($posts);
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
