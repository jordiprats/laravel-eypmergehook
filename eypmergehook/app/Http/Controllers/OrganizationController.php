<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use GitHub;
use Session;
use App\Repo;
use Carbon\Carbon;
use App\Organization;
use Github\ResultPager;
use App\LinkedSocialAccount;

class OrganizationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public static function fetchGitHubRepos($organization, $github)
  {
    Log::info("OrganizationController::fetchGitHubRepos: ".$organization->nickname);
    $github_paginator  = new ResultPager($github);

    foreach ($github_paginator->fetchAll($github->users(), 'repositories', [$organization->nickname]) as $github_repo)
    {
      $repo = Repo::where(['clone_url' => $github_repo['clone_url']])->first();
      //print_r($github_repo);
      if(!$repo)
      {
        if($github_repo['fork'])
        {
          // print_r($github_repo);
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

        $repo = Repo::create([
            'repo_name'        => $github_repo['name'],
            'full_name'        => $github_repo['full_name'],
            'fork'             => $fork,
            'private'          => $is_private,
            'clone_url'        => $github_repo['clone_url'],
            'organization_id'  => $organization->id,
            'github_id'        => $github_repo['id'],
        ]);
      }
      else
      {
        // TODO: fer update de repo existent
      }

      //dispatch(new RepoReleasesUpdater(<buscar usuari amb permisos>, $organization->nickname, $repo->repo_name));
    }
  }
}
