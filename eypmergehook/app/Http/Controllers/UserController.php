<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use GitHub;
use Session;
use App\User;
use App\Repo;
use App\Organization;
use Github\ResultPager;
use App\LinkedSocialAccount;
use Carbon\Carbon;
use App\Jobs\RepoReleasesUpdater;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public static function fetchGitHubRepos($user, $github)
  {
    Log::info("UserController::fetchGitHubRepos: ".$user->nickname);
    // $repos = $github->users()->repositories($user->nickname);
    $github_paginator  = new ResultPager($github);

    foreach ($github_paginator->fetchAll($github->users(), 'repositories', [$user->nickname]) as $github_repo)
    {
      // echo $github_repo['full_name']."\n";

      $repo = Repo::where(['clone_url' => $github_repo['clone_url']])->first();

      if(!$repo)
      {
        if($github_repo['owner']['login']==$user->nickname)
        {
          if($github_repo['fork'])
          {
            #$repo = $client->api('repo')->showById(123456)
            $github_repo_extended=$github->repos()->showById($github_repo['id']);
            // print_r($github_repo_extended);

            $fork=$github_repo_extended['parent']['clone_url'];
          }
          else
          {
            $fork=NULL;
          }

          $is_private=$github_repo['private']?true:false;

          // echo "===\n";
          // echo "name: ".$github_repo['name']."\n";
          // echo "full_name: ".$github_repo['full_name']."\n";
          // echo "fork: ".$fork."\n";
          // echo "private: ".$is_private."\n";
          // echo "clone_url: ".$github_repo['clone_url']."\n";
          // echo "user_id: ".$user->id."\n";
          // echo "github_id: ".$github_repo['id']."\n";

          $repo = Repo::create([
              'repo_name'        => $github_repo['name'],
              'full_name'        => $github_repo['full_name'],
              'fork'             => $fork,
              'private'          => $is_private,
              'clone_url'        => $github_repo['clone_url'],
              'user_id'          => $user->id,
              'github_id'        => $github_repo['id'],
          ]);
        }
        else
        {
          // TODO: check for organitzation membership
        }
      }
      else
      {
        // TODO: fer update de repo existent
      }

      // $repo si es nou o existent, check releases
      //dispatch(new RepoReleasesUpdater($user->nickname, $user->nickname, $repo->repo_name));
    }
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
      'telegram_chatid'        => 'nullable|string|max:255',
      'telegram_notifications' => 'boolean',
    ));

    $user = Auth::user();

    Log::info("UserController::telegram_notifications: ".$request->telegram_notifications);

    $user->telegram_chatid=$request->telegram_chatid;
    $user->telegram_notifications=$request->telegram_notifications;
    $user->save();

    //flash data
    Session::flash('status', 'Profile updated!');
    Session::flash('status-class', 'alert-success');

    //redirect
    return view('users.edit')->with('user', Auth::user());
  }
}
