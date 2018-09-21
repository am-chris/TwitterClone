<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Post\Comment;
use App\User;
use App\Notifications\YouWereMentioned;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $lastPost = Post::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        preg_match_all('/(?:^|\s)#(\w+)/', $request->content, $postHashtags);

        foreach (array_unique($postHashtags[1]) as $key => $value) {
            Redis::zincrby('trending_hashtags', 1, str_replace('#', '', $value));
        }

        // Parse hashtags and add them to Redis
        $postContent = preg_replace('/(?:^|\s)#(\w+)/', ' <a href="/hashtag/$1">#$1</a>', $request->content);

        $post = new Post;
        if (isset($request->post_id)) {
            $post->post_id = $request->post_id;
        }
        $post->user_id = Auth::id();
        $post->content = $postContent;
        $post->save();

        // Check the body of the post for mentioned users
        preg_match_all('/\@([^\s\.]+)/', $post->content, $mentionedUsers);

        foreach ($mentionedUsers[1] as $username) {
            $user = User::whereUsername($username)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($post));
            }
        }

        if ($request->ajax()) {
            return response(['status' => 'The post was created.']);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($postId)
    {
        $post = Post::findOrFail($postId);

        $this->authorize('view', $post);

        $comments = Post::where('post_id', $post->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('posts.show', compact('post', 'comments'));
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
    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        preg_match_all('/#(\w+)/', $post->content, $postHashtags);
        
        // Decrement each hashtag's post count by one in the sorted list
        foreach (array_unique($postHashtags[1]) as $key => $value) {
            $hashtagCount = Redis::zscore('trending_hashtags', str_replace('#', '', $value));

            Redis::zrem('trending_hashtags', str_replace('#', '', $value));

            if ($hashtagCount - 1 >= 0) {
                Redis::zincrby('trending_hashtags', $hashtagCount - 1, str_replace('#', '', $value));
            }
        }

        $post->delete();

        if ($request->ajax()) {
            return response(['status' => 'The post was deleted.']);
        } else {
            Session::flash('success', 'The post was deleted.');
            return redirect()->back();
        }
    }
}
