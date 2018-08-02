<?php

namespace App\Http\Controllers\Post;

use Auth;
use Session;
use App\Models\Post;
use App\Models\Post\Share;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShareController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $postId)
    {
        $post_share = new Share;
        $post_share->post_id = $request->post_id;
        $post_share->user_id = $request->user_id;
        $post_share->save();

        $post = Post::where('id', $request->post_id)
            ->first();

        $post->share_count = $post->share_count + 1;
        $post->save();

        if ($request->ajax()) {
            return response(['status' => 'The post was shared.']);
        } else {
            Session::flash('success', 'The post was shared.');
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
        $post_share = Share::where('post_id', $request->post_id)
            ->where('user_id', $request->user_id)
            ->first();
        $post_share->delete();

        $post = Post::where('id', $request->post_id)
            ->first();

        $post->share_count = $post->share_count - 1;
        $post->save();

        if ($request->ajax()) {
            return response(['status' => 'The post was unshared.']);
        } else {
            Session::flash('success', 'The post was unshared.');
            return redirect()->back();
        }
    }
}
