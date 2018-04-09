<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
