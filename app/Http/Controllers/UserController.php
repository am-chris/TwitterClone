<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::where('username', $username)
            ->first();

        if (count($user) == 0) {
            abort(404);
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
    {
        $user = User::where('username', $username)
            ->first();

        if (count($user) == 0) {
            abort(404);
        }

        if (Auth::id() !== $user->id) {
            return redirect('/');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        $user = User::where('username', $username)
            ->first();

        if (count($user) == 0) {
            abort(404);
        }

        if (Auth::id() !== $user->id) {
            return redirect('/');
        }

        $request->validate([
            'bio' => 'nullable|max:100',
        ]);

        // Strip symbols from new username
        $desired_username = str_replace(['~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '|', '{', '}', ',', '.', '?', ' '], '', $request->username);

        // If the username is already taken, throw an error
        $user2 = User::where('username', $desired_username)
            ->first();

        if (count($user2) && $user->id !== $user2->id) {
            Session::flash('error', 'That username is already taken. Choose a unique username.');
            return redirect()->back();
        }

        // If the user changes their username, remove "Verified" status from their account
        // to prevent verified users from pretending to be other people
        if ($desired_username !== $user->username && $user->verified == 1) {
            $user->verified = 0;
        }

        $user->name = $request->name;
        $user->username = $desired_username;
        $user->bio = preg_replace('/\r|\n/', '', $request->bio);
        $user->save();

        Session::flash('success', 'Your profile was updated.');
        return redirect('/' . $user->username . '/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function followers($username)
    {
        $user = User::where('username', $username)
            ->first();

        return view('users.followers', compact('user'));
    }

    public function following($username)
    {
        $user = User::where('username', $username)
            ->first();

        return view('users.following', compact('user'));
    }
}
