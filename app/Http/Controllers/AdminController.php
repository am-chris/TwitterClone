<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Post;
use App\Models\Post\Like;
use App\Models\Post\Share;
use App\Models\User\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::select('id')->get();
        $users = User::select('id')->get();
        $likes = Like::select('id')->get();
        $shares = Share::select('id')->get();
        $reports = Report::orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('posts', 'users', 'likes', 'shares', 'reports'));
    }
}
