<?php

namespace App\Http\Controllers\Api;

use Auth;
use Redis;
use Response;
use App\Models\Post;
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
        $sharedPostIds = Auth::user()->shares();

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
        $blockedUserIds = Auth::user()->blocking();
        
        $sharedPostIds = Auth::user()->shares();

        $posts = Post::with('user')
            ->whereNotIn('user_id', $blockedUserIds)
            ->where('user_id', $userId)
            ->orWhereIn('id', $sharedPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Response::json($posts);
    }
}
