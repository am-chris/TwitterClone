<?php

namespace App\Http\Controllers\User;

use Auth;
use Redis;
use Session;
use App\Models\Follow;
use App\Models\User\Block;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
    {
        // If the user is trying to block themself, throw an error
        if (Auth::id() == $request->user_id) {
            if ($request->ajax()) {
                return response(['status' => 'You can\'t block yourself.']);
            } else {
                Session::flash('error', 'You can\'t block yourself.');
                return redirect()->back();
            }
        }

        // If the user is already blocked, throw an error
        $blockedUser = Block::where('blocker_id', $request->current_user_id)
            ->where('blocked_id', $request->user_id)
            ->first();

        if (!is_null($blockedUser)) {
            if ($request->ajax()) {
                return response(['status' => 'The user is already blocked.']);
            } else {
                Session::flash('error', 'The user is already blocked.');
                return redirect()->back();
            }
        }

        // Unfollow the user if applicable
        $follow = Follow::where('followed_id', $request->user_id)
            ->where('follower_id', $request->current_user_id)
            ->first();

        if (!is_null($follow)) {
            $follow->delete();
        }

        // Block the user
        Redis::zadd('blockers:' . $request->user_id, time(), $request->current_user_id);
        Redis::zadd('blocking:' . $request->current_user_id, time(), $request->user_id);

        if ($request->ajax()) {
            return response(['status' => 'Blocked the user.']);
        } else {
            Session::flash('success', 'Blocked the user.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $userId)
    {
        // If the user isn't blocked, throw an error
        $blockedUser = Block::where('blocker_id', $request->current_user_id)
            ->where('blocked_id', $request->user_id)
            ->first();

        if (is_null($blockedUser)) {
            if ($request->ajax()) {
                return response(['status' => 'The user isn\'t blocked.']);
            } else {
                Session::flash('error', 'The user isn\'t blocked.');
                return redirect()->back();
            }
        }

        // Unblock the user
        Redis::zrem('blockers:' . $request->user_id, time(), $request->current_user_id);
        Redis::zrem('blocking:' . $request->current_user_id, time(), $request->user_id);

        if ($request->ajax()) {
            return response(['status' => 'Unblocked the user.']);
        } else {
            Session::flash('success', 'Unblocked the user.');
            return redirect()->back();
        }
    }
}
