<?php

namespace App\Http\Controllers\Api;

use Auth;
use Response;
use App\Models\Follow;
use App\Models\Post;
use App\Models\Post\Share;
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
        $blocked_user_ids = Block::where('blocker_id', $user_id)
            ->pluck('blocked_id')
            ->toArray();

        $users_im_following = [];

        $users_im_following = Follow::where('follower_id', $user_id)
            ->whereNotIn('followed_id', $blocked_user_ids)
            ->pluck('followed_id')
            ->toArray();

        array_push($users_im_following, $user_id); // Add current user to array

        $shared_post_ids = Share::whereIn('user_id', $users_im_following)
            ->pluck('post_id');

        $posts = Post::with('user')
            ->whereIn('user_id', $users_im_following)
            ->orWhereIn('id', $shared_post_ids)
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
    public function individual($user_id)
    {
        $blocked_user_ids = Block::where('blocker_id', $user_id)
            ->pluck('blocked_id')
            ->toArray();

        $shared_post_ids = Share::where('user_id', $user_id)
            ->pluck('post_id');

        $posts = Post::with('user')
            ->where('user_id', $user_id)
            ->orWhereIn('id', $shared_post_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Response::json($posts);
    }
}
