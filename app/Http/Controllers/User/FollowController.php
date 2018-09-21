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
    public function store(Request $request, User $user)
    {
        if (Auth::id() == $user->id) {
            if ($request->ajax()) {
                return response(['status' => 'You can\'t follow yourself.']);
            } else {
                Session::flash('error', 'You can\'t follow yourself.');
                return redirect()->back();
            }
        }

        Auth::user()->follow($user);

        return redirect()->back();
    }

    /**
     * Unfollow a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        Auth::user()->unfollow($user);

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
    public function approve_follow_request(Request $request, User $user)
    {
        Auth::user()->approveFollowRequest($user);

        return redirect()->back();
    }

    /**
     * Deny a follow requested by another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function deny_follow_request(Request $request, User $user)
    {
        Auth::user()->denyFollowRequest($user);

        return redirect()->back();
    }
}
