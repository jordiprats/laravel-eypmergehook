<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function userPlatforms($user)
  {
    return view('home')->with('platforms', User::where('username', $user)->first()->platforms)->with('user', User::where('username', $user)->first());
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit()
  {
    return view('users.edit')->with('user', Auth::user());
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    //validate
    $this->validate($request, array(
      'name' => 'required|string|max:255',
    ));

    $user = Auth::user();

    $user->save();

    //flash data
    Session::flash('status', 'Profile updated!');
    Session::flash('status-class', 'alert-success');

    //redirect
    return view('users.edit')->with('user', Auth::user());
  }
}
