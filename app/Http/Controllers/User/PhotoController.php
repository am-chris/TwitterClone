<?php

namespace App\Http\Controllers\User;

use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'file' => 'dimensions:min_width=80,min_height=80,max_width=400,max_height=400'
        ]);

        $path = Storage::putFile('users/photos', $request->file('file'));

        $user = User::findOrFail($user_id);
        $user->photo_url = $path;
        $user->save();

        if ($request->ajax()) {
            return response(['status' => 'The photo was uploaded.']);
        } else {
            Session::flash('success', 'The photo was uploaded.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        Storage::delete($user->photo_url);

        $user->photo_url = 'users/photos/mysteryman.png';
        $user->save();

        if ($request->ajax()) {
            return response(['status' => 'The photo was deleted.']);
        } else {
            Session::flash('success', 'The photo was deleted.');
            return redirect()->back();
        }
    }
}
