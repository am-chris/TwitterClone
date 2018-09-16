<?php

namespace App\Http\Controllers\Api;

use Auth;
use Redis;
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
        $sharedPostIds = Share::whereIn('user_id', Auth::user()->following())
            ->pluck('post_id');

        $posts = Post::with('user')
            ->whereNotIn('user_id', Auth::user()->blocking())
            ->whereIn('user_id', array_merge(Auth::user()->following(), ['0' => Auth::id()]))
            ->orWhereIn('id', $sharedPostIds)
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
