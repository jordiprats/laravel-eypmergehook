<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use GitHub;
use Session;
use App\Repo;
use Github\ResultPager;
use Carbon\Carbon;
use App\Jobs\RepoReleasesUpdater;

class RepoReleaseController extends Controller
{
  public static function fetchGitHubRepoReleases($nickname, $repo, $github)
  {
    Log::info("RepoReleaseController::fetchGitHubRepoReleases: ".$nickname."/".$repo);
    $github_paginator  = new ResultPager($github);

    foreach ($github_paginator->fetchAll($github->repos()->releases(), 'all', [$nickname, $repo]) as $github_release)
    {
      dd($github_release);
    }
  }
}
