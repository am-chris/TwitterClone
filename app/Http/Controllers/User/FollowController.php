<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Models\Follow;
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
}
