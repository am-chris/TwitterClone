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
    public function index($userId)
    {
        $blockedUserIds = Block::where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->toArray();

        $usersImFollowing = [];

        $usersImFollowing = Follow::where('follower_id', $userId)
            ->whereNotIn('followed_id', $blockedUserIds)
            ->pluck('followed_id')
            ->toArray();

        array_push($usersImFollowing, $userId); // Add current user to array

        $sharedPostIds = Share::whereIn('user_id', $usersImFollowing)
            ->pluck('post_id');

        $posts = Post::with('user')
            ->whereIn('user_id', $usersImFollowing)
            ->orWhereIn('id', $sharedPostIds)
            ->whereNotIn('user_id', $blockedUserIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Response::json($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function individual($userId)
    {
        $blockedUserIds = Block::where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->toArray();

        $sharedPostIds = Share::where('user_id', $userId)
            ->pluck('post_id');

        $posts = Post::with('user')
            ->where('user_id', $userId)
            ->orWhereIn('id', $sharedPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Response::json($posts);
    }
}
