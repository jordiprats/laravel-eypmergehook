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
use App\Organization;
use Github\ResultPager;
use Carbon\Carbon;
use App\Jobs\RepoReleasesUpdater;

class RepoReleaseController extends Controller
{

  public static function fetchGitHubRepoReleases($nickname, $repo_name, $github)
  {
    Log::info("RepoReleaseController::fetchGitHubRepoReleases: ".$nickname."/".$repo_name);
    $github_paginator  = new ResultPager($github);

    if(User::where(['nickname' => $nickname])->count() == 1)
    {
      $user = User::where(['nickname' => $nickname])->first();
      $repo = Repo::where(['full_name' => $nickname."/".$repo_name, 'user_id' => $user->id])->first();
    }
    elseif(Organization::where(['nickname' => $nickname])->count() == 1)
    {
      $organization = Organization::where(['nickname' => $nickname])->first();
      $repo = Repo::where(['full_name' => $nickname."/".$repo_name, 'organization_id' => $organization->id])->first();
    }
    else
    {
        Log::info("RepoReleaseController::fetchGitHubRepoReleases: ".$nickname."/".$repo_name." - NOT FOUND");
        return;
    }

    if($repo->repo_analyzed_on && $repo->is_puppet_module)
    {
      if($user->autoreleasetags || $repo->autoreleasetags)
      {
        foreach ($github_paginator->fetchAll($github->repos(), 'tags', [$nickname, $repo_name]) as $github_tag)
        {
          if(!$repo->reporeleases->contains('release_name', $github_tag['name']))
          {
            $github->repos()->releases()->create($nickname, $repo_name, array('tag_name' => $github_tag['name'], 'name' => $github_tag['name'], 'body' => $github_tag['name'], 'target_commitish' => 'master'));
          }
        }
      }

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
}
