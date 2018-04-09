<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use GitHub;
use Session;
use App\Repo;
use App\RepoRelease;
use App\User;
use Github\ResultPager;
use Carbon\Carbon;
use App\Jobs\RepoReleasesUpdater;

class RepoReleaseController extends Controller
{
  public static function fetchGitHubRepoReleases($nickname, $repo_name, $github)
  {
    Log::info("RepoReleaseController::fetchGitHubRepoReleases: ".$nickname."/".$repo_name);
    $github_paginator  = new ResultPager($github);

    $user = User::where(['nickname' => $nickname])->first();
    $repo = Repo::where(['full_name' => $nickname."/".$repo_name, 'user_id' => $user->id])->first();

    foreach ($github_paginator->fetchAll($github->repos()->releases(), 'all', [$nickname, $repo_name]) as $github_release)
    {
      if(!$repo->reporeleases->contains('release_name', $github_release['name']))
      {
        RepoRelease::create([
          'release_name' => $github_release['name'],
          'repo_id'      => $repo->id,
        ]);
      }
    }
  }
}
