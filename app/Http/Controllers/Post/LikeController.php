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
    public function store(Request $request, $postId)
    {
        // If the post IDs do not match, the user is up to some shenanigans
        if ($postId != $request->post_id) {
            if ($request->ajax()) {
                return response(['status' => 'The post IDs do not match.']);
            } else {
                Session::flash('error', 'The post IDs do not match.');
                return redirect()->back();
            }
        }

        $like = new Like;
        $like->user_id = $request->user_id;
        $like->post_id = $request->post_id;
        $like->save();

        $post = Post::where('id', $postId)
            ->first();

        $post->like_count = $post->like_count + 1;
        $post->save();

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
    public function destroy(Request $request, $postId)
    {
        $post_like = Like::where('post_id', $request->post_id)
            ->where('user_id', $request->user_id)
            ->first();
        $post_like->delete();

        $post = Post::where('id', $request->post_id)
            ->first();

        $post->like_count = $post->like_count - 1;
        $post->save();

        if ($request->ajax()) {
            return response(['status' => 'The post was unliked.']);
        } else {
            Session::flash('success', 'The post was unliked.');
            return redirect()->back();
        }
    }
}
