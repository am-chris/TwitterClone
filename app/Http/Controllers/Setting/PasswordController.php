<?php

namespace App\Http\Controllers\Setting;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back();
    }
}
