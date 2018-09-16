<?php

namespace App\Http\Controllers\User;

use Auth;
use Redis;
use Session;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    /**
     * Follow a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
    {
        if (Auth::id() == $userId) {
            if ($request->ajax()) {
                return response(['status' => 'You can\'t follow yourself.']);
            } else {
                Session::flash('error', 'You can\'t follow yourself.');
                return redirect()->back();
            }
        }

        // Check if current user is already following this user
        $currentUserFollowingUser = Follow::where('followed_id', $userId)
            ->where('follower_id', Auth::id())
            ->first();

        if (!empty($currentUserFollowingUser)) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        if ($user->private == true) {
            $follow_request = new FollowRequest;
            $follow_request->followed_id = $request->user_id;
            $follow_request->follower_id = $request->current_user_id;
            $follow_request->save();
        } else {
            Redis::zadd('followers:' . $request->user_id, time(), $request->current_user_id);
            Redis::zadd('following:' . $request->current_user_id, time(), $request->user_id);
        }
    }

    /**
     * Unfollow a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $userId)
    {
        Redis::zrem('followers:' . $userId, time(), $request->current_user_id);
        Redis::zrem('following:' . $request->current_user_id, time(), $userId);

        if ($request->ajax()) {
            return response(['status' => 'Unfollowed the user.']);
        } else {
            Session::flash('success', 'Unfollowed the user.');
            return redirect()->back();
        }
    }

    /**
     * Approve a follow requested by another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function approve_follow_request(Request $request, $userId)
    {
        $follow_request = FollowRequest::where('follower_id', $userId)
            ->where('followed_id', $request->current_user_id)
            ->first();

        Redis::zadd('followers:' . $request->user_id, time(), $request->current_user_id);
        Redis::zadd('following:' . $request->current_user_id, time(), $request->user_id);

        $follow_request->delete();

        return redirect()->back();
    }

    /**
     * Deny a follow requested by another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function deny_follow_request(Request $request, $userId)
    {
        $followRequest = FollowRequest::where('follower_id', $userId)
            ->where('followed_id', $request->current_user_id)
            ->first();

        $followRequest->delete();

        return redirect()->back();
    }
}
