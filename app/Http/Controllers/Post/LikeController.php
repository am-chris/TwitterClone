<?php

namespace App\Http\Controllers\Post;

use Auth;
use Session;
use App\Models\Post;
use App\Models\Post\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        Auth::user()->likePost($post);

        if ($request->ajax()) {
            return response(['status' => 'The post was liked.']);
        } else {
            Session::flash('success', 'The post was liked.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        Auth::user()->unlikePost($post);

        if ($request->ajax()) {
            return response(['status' => 'The post was unliked.']);
        } else {
            Session::flash('success', 'The post was unliked.');
            return redirect()->back();
        }
    }
}
