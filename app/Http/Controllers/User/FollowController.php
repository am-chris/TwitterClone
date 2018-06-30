<?php

namespace App\Http\Controllers\User;

use Auth;
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
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request, $user_id)
    {
        if ($user_id !== $request->user_id) {

        }

        if (Auth::id() == $user_id) {
            if ($request->ajax()) {
                return response(['status' => 'You can\'t follow yourself.']);
            } else {
                Session::flash('error', 'You can\'t follow yourself.');
                return redirect()->back();
            }
        }

        $user = User::findOrFail($user_id);

        if ($user->private == 1) {
            $follow_request = new FollowRequest;
            $follow_request->followed_id = $request->user_id;
            $follow_request->follower_id = $request->current_user_id;
            $follow_request->save();

            if ($request->ajax()) {
                return response(['status' => 'Sent follow request.']);
            } else {
                Session::flash('success', 'Sent follow request.');
                return redirect()->back();
            }
        } else {
            $follow = new Follow;
            $follow->followed_id = $request->user_id;
            $follow->follower_id = $request->current_user_id;
            $follow->save();

            if ($request->ajax()) {
                return response(['status' => 'Followed the user.']);
            } else {
                Session::flash('success', 'Followed the user.');
                return redirect()->back();
            }
        }
    }

    /**
     * Unfollow a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unfollow(Request $request, $user_id)
    {
        $follow = Follow::where('followed_id', $user_id)
            ->where('follower_id', $request->current_user_id)
            ->first();

        $follow->delete();

        if ($request->ajax()) {
            return response(['status' => 'Unfollowed the user.']);
        } else {
            Session::flash('success', 'Unfollowed the user.');
            return redirect()->back();
        }
    }

    public function approve_follow_request(Request $request, $user_id)
    {
        $follow_request = FollowRequest::where('follower_id', $user_id)
            ->where('followed_id', $request->current_user_id)
            ->first();

        $follow = new Follow;
        $follow->followed_id = $request->current_user_id;
        $follow->follower_id = $request->user_id;
        $follow->save();

        $follow_request->delete();

        if ($request->ajax()) {
            return response(['status' => 'Approved the follow request.']);
        } else {
            Session::flash('success', 'Approved the follow request.');
            return redirect()->back();
        }
    }

    public function cancel_follow_request(Request $request, $user_id)
    {
        $follow_request = FollowRequest::where('follower_id', $user_id)
            ->where('followed_id', $request->current_user_id)
            ->first();

        $follow_request->delete();

        if ($request->ajax()) {
            return response(['status' => 'Canceled the follow request.']);
        } else {
            Session::flash('success', 'Canceled the follow request.');
            return redirect()->back();
        }
    }
}
