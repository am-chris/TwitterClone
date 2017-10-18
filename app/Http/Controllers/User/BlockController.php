<?php

namespace App\Http\Controllers\User;

use Auth;
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
    public function block(Request $request, $user_id)
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
        $blocked_user = Block::where('blocker_id', $request->current_user_id)
            ->where('blocked_id', $request->user_id)
            ->first();

        if (count($blocked_user) > 0) {
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

        if (count($follow)) {
            $follow->delete();
        }

        // Block the user
        Auth::user()->block($request->user_id);

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
    public function unblock(Request $request, $user_id)
    {
        // If the user isn't blocked, throw an error
        $blocked_user = Block::where('blocker_id', $request->current_user_id)
            ->where('blocked_id', $request->user_id)
            ->first();

        if (count($blocked_user) == 0) {
            if ($request->ajax()) {
                return response(['status' => 'The user isn\'t blocked.']);
            } else {
                Session::flash('error', 'The user isn\'t blocked.');
                return redirect()->back();
            }
        }

        // Unblock the user
        $blocked_user->delete();

        if ($request->ajax()) {
            return response(['status' => 'Unblocked the user.']);
        } else {
            Session::flash('success', 'Unblocked the user.');
            return redirect()->back();
        }
    }
}
