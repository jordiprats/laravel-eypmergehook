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
use App\Jobs\AnalyzeGitRepo;

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

            $fork=$github_repo_extended['parent']['full_name'];
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

          //analitzar repo
          try
          {
            dispatch(new AnalyzeGitRepo($user->nickname, $repo->repo_name));
          }
          catch(\Exception $e)
          {
            Log::info("-_(._.)_-");
            Log::info($e);
          }

          RepoReleaseController::fetchGitHubRepoReleases($user->nickname, $github_repo['name'], $github);
        }
        else
        {
          // TODO: check for organitzation membership
        }
      }
      else
      {
        // TODO: fer update de repo existent

        // TODO: llegir releases només si cal
        RepoReleaseController::fetchGitHubRepoReleases($user->nickname, $repo->repo_name, $github);
      }

      // $repo si es nou o existent, check releases
      //dispatch(new RepoReleasesUpdater($user->nickname, $user->nickname, $repo->repo_name));
    }
  }

  public function getUserInfo($nickname)
  {
    if(User::where('nickname', $nickname)->count() == 1)
    {
      return view('home')
              ->with('user',      User::where('nickname', $nickname)->first())
              ->with('repos',     User::where('nickname', $nickname)->first()->repos)
              ->with('platforms', User::where('nickname', $nickname)->first()->platforms);
    }
    elseif(Organization::where('nickname', $nickname)->count() == 1)
    {
      return view('home')
              ->with('user',      Organization::where('nickname', $nickname)->first())
              ->with('repos',     Organization::where('nickname', $nickname)->first()->repos)
              ->with('platforms', array());
    }
    else
    {
      abort(404);
    }
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
      'webhook_password'       => 'nullable|string|max:255',
      'webhook'                => 'boolean',
      'autoreleasetags'        => 'boolean',
      'autotagforks'           => 'boolean',

    ));

    $user = Auth::user();

    $user->telegram_chatid=$request->telegram_chatid;
    $user->telegram_notifications=$request->telegram_notifications==1?true:false;

    $user->webhook_password=$request->webhook_password;
    $user->webhook=$request->webhook==1?true:false;

    $user->autoreleasetags=$request->autoreleasetags==1?true:false;
    $user->autotagforks=$request->autotagforks==1?true:false;

    $user->save();

    //flash data
    Session::flash('status', 'Profile updated!');
    Session::flash('status-class', 'alert-success');

    //redirect
    return view('users.edit')->with('user', Auth::user());
  }
}
