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
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:500', 'dimensions:min_width=100,min_height=100,max_width=403,max_height=403'],
        ]);

        $user = User::findOrFail($userId);
        
        // Get original cover photo's path, we will use it to delete the original photo after the new photo is uploaded
        $originalPhoto = $user->photo_url;

        $user->update([
            'photo_url' => $request->file('file')->store('users/photos')
        ]);

        Storage::delete($originalPhoto);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $userId)
    {
        $user = User::findOrFail($userId);

        if (Auth::id() !== $userId && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        Storage::delete($user->photo_url);

        $user->photo_url = 'users/photos/mysteryman.png';
        $user->save();
    }
}
