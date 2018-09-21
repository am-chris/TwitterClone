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
    public function store(Request $request, User $user)
    {
        // If the user is trying to block themself, throw an error
        if (Auth::id() == $user->id) {
            if ($request->ajax()) {
                return response(['status' => 'You can\'t block yourself.']);
            } else {
                Session::flash('error', 'You can\'t block yourself.');
                return redirect()->back();
            }
        }

        // Unfollow the user if applicable
        Auth::user()->unfollow($user);

        // Block the user
        Auth::user()->block($user);

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
    public function destroy(Request $request, User $user)
    {
        // Unblock the user
        Auth::user()->unblock($user);

        if ($request->ajax()) {
            return response(['status' => 'Unblocked the user.']);
        } else {
            Session::flash('success', 'Unblocked the user.');
            return redirect()->back();
        }
    }
}
