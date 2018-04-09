<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use GitHub;
use Session;
use App\Repo;
use App\User;
use Github\ResultPager;
use Carbon\Carbon;
use App\Jobs\RepoReleasesUpdater;

class RepoReleaseController extends Controller
{
  public static function fetchGitHubRepoReleases($nickname, $repo, $github)
  {
    Log::info("RepoReleaseController::fetchGitHubRepoReleases: ".$nickname."/".$repo);
    $github_paginator  = new ResultPager($github);

    $user = User::where(['nickname' => $nickname])->first();
    $repo = Repo::where(['full_name' => $nickname."/".$repo, 'user_id' => $user->id])->first();

    try
    {
      foreach ($github_paginator->fetchAll($github->repos()->releases(), 'all', [$nickname, $repo]) as $github_release)
      {
        if(!$repo->reporeleases->contains('release_name', $release['name']))
        {
          RepoRelease::create([
            'release_name' => $release['name'],
            'repo_id'      => $repo->id,
          ]);
        }
      }
    }
    catch (Exception $e)
    {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }
}
