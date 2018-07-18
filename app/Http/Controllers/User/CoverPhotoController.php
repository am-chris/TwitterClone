<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoverPhotoController extends Controller
{
    /**
     * Upload a cover photo and place it in the appropriate filesystem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2000', 'dimensions:max_width=1925,max_height=505'],
        ]);
        
        $user = User::findOrFail($userId);
        
        // Get original cover photo's path, we will use it to delete the original photo after the new photo is uploaded
        $originalCoverPhotoUrl = $user->cover_photo_url;

        Auth::user()->update([
            'cover_photo_url' => $request->file('file')->store('users/cover_photos')
        ]);

        Storage::delete($originalCoverPhotoUrl);
    }

    /**
     * Remove the specified cover photo from the appropriate filesystem.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        Storage::delete($user->cover_photo_url);

        $user->cover_photo_url = 'users/cover_photos/default_cover_photo.png';
        $user->save();
    }
}