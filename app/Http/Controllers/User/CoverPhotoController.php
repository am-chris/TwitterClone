<?php

namespace App\Http\Controllers\User;

use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoverPhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $user_id)
    {
        $path = Storage::putFile('users/cover_photos', $request->file('file'));

        $user = User::findOrFail($user_id);
        $user->cover_photo_url = $path;
        $user->save();

        if ($request->ajax()) {
            return response(['status' => 'The cover photo was uploaded.']);
        } else {
            Session::flash('success', 'The cover photo was uploaded.');
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

        Storage::delete($user->cover_photo_url);

        $user->cover_photo_url = 'users/cover_photos/default_cover_photo.png';
        $user->save();

        if ($request->ajax()) {
            return response(['status' => 'The cover photo was deleted.']);
        } else {
            Session::flash('success', 'The cover photo was deleted.');
            return redirect()->back();
        }
    }
}
